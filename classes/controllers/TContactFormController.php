<?php
namespace dr\classes\controllers;

use dr\classes\controllers\TControllerAbstract;
use dr\classes\dom\tag\form\InputSubmit;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\Textarea;
use dr\classes\dom\validator\Emailaddress;
use dr\classes\dom\FormGenerator;
use dr\classes\dom\tag\form\InputButton;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\validator\CheckboxChecked;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;
use dr\classes\mail\TMailSend;
use dr\classes\patterns\TSpamDetector;

/**
 * Description of TContactForm
 *
 * This class represents a standard email contact form.
 * Extend this class if you want more fields or functionality
 * 
 * With this class you can:
 * - send emails with this form 
 * - good filters on all fields to prevent email injection
 * - automatically filter on spam
 * - honeypot included
 * 
 * @todo checkbox optioneel: I understand: only business related and product support inquiries will be answered
 * @todo similar_text() implementeren op herkenning blocked
 * @todo blacklist ip adressen bots bijhouden
  * 
 * HONEYPOT:
 * This class uses a honeypot to 'catch' bots.
 * A honeypot field (firstname) looks like a normal field that a user can fill in,
 * however this field is not shown to a human user (hidden with css).
 * A bot fills out this field, so we know it is spam
 * 
 * with a simple javascript, you can hide the parent elements of the honeypot
        const objNodes = document.getElementsByClassName("letitbee");
        let iNodeCount = objNodes.length;
        for (i = 0; i < iNodeCount; i++) 
        {
            // objNodes[i].parentElement.style.visibility = 'hidden';
            objNodes[i].parentElement.style.height = 0; //otherwise it still takes up space
            objNodes[i].parentElement.style.overflow = 'hidden';
        } 
 * 
 * 
 * @author dennis renirie
 * 11 mrt 2022: TContactFormController created
 * 18 jan 2023: TContactFormController toevoegingen. Senden email werkt nog niet
 * 19 jan 2023: TContactFormController updates + senden email werkt nu
 * 20 jan 2023: TContactFormController optimized: email adres check works, email via template works, fields removed werkt
 *
 */

abstract class TContactFormController extends TControllerAbstract
{
    //form elements: these are availble to the child class. for example to change css classes or translations or whatever
    protected $objForm = null;//dr\classes\dom\FormGenerator
    protected $objHoneyPot = null;//dr\classes\dom\InputText --> visually hidden with js/css, this field is filled out by bots but is not visible to a human user. this is how we can detect spam
    protected $objFrom = null;//dr\classes\dom\InputText
    protected $objEmailAddress = null;//dr\classes\dom\InputText
    protected $objCategoriesOptionSelect = null; //dr\classes\dom\Option --> <select><option> list with email topics. Either <option> or <input type="text"> will be used
    protected $objTopicInputText = null; //dr\classes\dom\InputText --> if no <select>, then use this edit box for email topic
    protected $objChkNoLinks = null;//dr\classes\dom\Check
    protected $objMessage = null; //dr\classes\dom\InputTextarea
    protected $objSubmit = null;//dr\classes\dom\tag\form\InputSubmit

    //internal vars
    protected $arrCategories = array();//1d array with email topics wich are thrown into $objTopicCategoriesSelect. THIS CAN BE NULL
    protected $arrBlockedSenderNames = array();//1d array blacklist with bad senders "eric jones"
    protected $arrBlockedSenderAddresses = array();//1d array blacklist with bad senders "eric jones"
    protected $arrBlockedTopics = array();//1d array blacklist with bad subjects/topics

    protected $objSpamName = null;//TSpamDetector class
    protected $objSpamSubject = null;//TSpamDetector class
    protected $objSpamBody = null;//TSpamDetector class

    protected $bHyperLinksAllowed = false;//hyperlinks are regarded as evil. checkbox will be added and hyperlinks will be filtered
    protected $bRecaptchaV3Use = false;//use recaptcha v3 for the form?

    const HONEYPOT_FIELDNAME = 'edtFirstName1'; //it needs to be a human readable, we can't name this 'honeypot' otherwise bots will know
    const HONEYPOT_CSSCLASS = 'contactform_firstname1'; //it needs to be a human readable we can't name this 'honeypot'

    const SPAM_ERRORMESSAGE = 'Unexpected XSAM error occured'; //error message when bots/spam detected. This is deliberately very vague not to tip off spammers

    public function __construct()
    {
        $this->arrCategories = $this->getCategories();

        $this->arrBlockedSenderNames[] = 'eric jones'; 
        $this->arrBlockedSenderNames[] = 'zoe tan'; 
        $this->arrBlockedSenderNames[] = 'pablo neidig'; 
        $this->arrBlockedSenderNames[] = 'James J. Fair'; 
        $this->arrBlockedSenderNames[] = 'Mark Schaefer'; 

        $this->arrBlockedSenderAddresses[] = 'eric.jones'; //as in "eric.jones.z.mail@gmail.com"
        $this->arrBlockedSenderAddresses[] = 'ericjones'; //as in "ericjonesmyemail@gmail.com"
        $this->arrBlockedSenderAddresses[] = 'marks@nutricompany.com'; //Mark Schaefer

        $this->arrBlockedTopics[] = 'talk talk'; //Turn SurfSurfSurf into Talk Talk Talk
        $this->arrBlockedTopics[] = 'Why not TALK with your leads'; //eric jones spam
        $this->arrBlockedTopics[] = 'how to turn eyeballs into phone calls'; //eric jones spam
        $this->arrBlockedTopics[] = 'Who needs eyeballs you need BUSINESS'; //eric jones spam
        $this->arrBlockedTopics[] = 'Strike when the irons hot'; //eric jones spam
        $this->arrBlockedTopics[] = 'Instead congrats';//eric jones spam
        $this->arrBlockedTopics[] = 'Your site more leads'; //eric jones spam        
        $this->arrBlockedTopics[] = 'Cool website'; //eric jones spam        
        $this->arrBlockedTopics[] = 'There they go';         
        $this->arrBlockedTopics[] = 'Try this get more leads'; //eric jones spam        
        $this->arrBlockedTopics[] = 'how to turn eyeballs into phone calls'; //eric jones spam        
        $this->arrBlockedTopics[] = 'hi'; 
        $this->arrBlockedTopics[] = 'delivery'; //as in "RE: Delivery For You"
        $this->arrBlockedTopics[] = 'Online Pharmacy'; //as in "Canadian Online Pharmacy"
        $this->arrBlockedTopics[] = 'canadian'; //as in "Canadian Online Pharmacy"
        $this->arrBlockedTopics[] = 'shop'; //as in "men online shop"
        $this->arrBlockedTopics[] = 'confirm'; //as in "confirm Your Order"
        $this->arrBlockedTopics[] = 're:'; //implying a reply, which is weird for an online form
        $this->arrBlockedTopics[] = 'FIND YOUR PERFECT PARTNER IN THE USA'; 
        $this->arrBlockedTopics[] = 'Quick question about '.GLOBAL_PATH_DOMAIN; //as in "Quick question about learnhowtoproducemusic.com"

        $this->objSpamName = new TSpamDetector();
        $this->objSpamSubject = new TSpamDetector();
        $this->objSpamBody = new TSpamDetector();

        $this->populateInternal();

        //handle form submit
        if ($this->objForm->isFormSubmitted())
            $this->handleFormSubmit();           
        
        parent::__construct();//renders controller

    }

    /**
     * hyperlinks allowed
     * Not allowed will add a checkbox:hyperlinks will be automatically removed from messages. The reader can't see nor click them.
     * 
     * @param bool $bAllowed
     */
    public function setHyperlinksAllowed($bAllowed)
    {
        $this->bHyperLinksAllowed = $bAllowed;
    }

    /**
     * use recapcha v3 on form
     * 
     * @param bool $bUse
     */
    public function setRecaptchaV3Use($bUse)
    {
        $this->bRecaptchaV3Use = $bUse;

        if ($this->objForm) //form object can be null before parent constructor is called in child class constructor
            $this->objForm->setRecaptchaV3Use($bUse);
    }

    /**
     * use recapcha v3 on form
     * 
     * returns alsways false when GLOBAL_GOOGLE_RECAPTCHAV3_USE == false;
     */
    public function getRecaptchaV3Use()
    {
        if (GLOBAL_GOOGLE_RECAPTCHAV3_USE == false)
            return false;

        return $this->bRecaptchaV3Use;

    }    

    /**
     * handle the form being submitted
     */
    private function handleFormSubmit()
    {
        if (!$this->objForm->isValid())
        {
            $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_inputerror', 'Form not submitted due to an input error'));        
            $this->objForm->setSubmittedValuesAsValues();
            return false;
        }

        //=== run checks

        //check email address
        if (!$this->checkEmailAddress())
            return false;

        //check sender names
        if (!$this->checkSenderName())
            return false;

        //check honeypot
        if (!$this->checkHoneyPot())
            return false;

        //check topics
        if (!$this->checkTopic())
            return false;
        
        //check body
        if (!$this->checkBody())
            return false;
     
        
        $this->sendEmail();
    }

    private function checkEmailAddress()
    {
        $sEmailAddress = '';
        $sEmailAddress = filter_var($this->objEmailAddress->getContentsSubmitted()->getValueAsString(), FILTER_SANITIZE_EMAIL);

        //check dirty characters in email address
        if ($sEmailAddress != $this->objEmailAddress->getContentsSubmitted()->getValueAsString())
        {
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Email address "'.$sEmailAddress.'" contains dirty characters (email address shown in this error message is filtered)');
            $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_emailaddressdirty', TContactFormController::SPAM_ERRORMESSAGE));//be vague about exact cause
            $this->removeAllFields();
            return false;
        }

        //check blocked list
        foreach ($this->arrBlockedSenderAddresses as $sBlocked)
        {
            if (stripos($sEmailAddress, $sBlocked) !== false) //if exists
            {
                logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Email address "'.$sEmailAddress.'" is on blacklist. Entry: "'.$sBlocked.'"');
                $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_emailaddressonblacklist', TContactFormController::SPAM_ERRORMESSAGE));//be vague about exact cause
                // die(transw('message_contactform_emailaddressonblacklist', 'Suspicious activity detected'));
                $this->removeAllFields();
                return false;
            }
        }

        return true;
    }

    private function checkSenderName()
    {
        $sSender = $this->objFrom->getContentsSubmitted()->getValueAsString();

        //check blocked list
        foreach ($this->arrBlockedSenderNames as $sBlocked)
        {
            if (stripos($sSender, $sBlocked) !== false) //if exists
            {
                logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Sender name "'.$sSender.'" is on blacklist. Entry: "'.$sBlocked.'"');
                $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_sendernameonblacklist', TContactFormController::SPAM_ERRORMESSAGE));//be vague about exact cause
                $this->removeAllFields();
                return false;
            }
        }        


        //check for spam
        $this->objSpamName->setText($sSender);

        $this->objSpamName->detectURLs();
        $this->objSpamName->detectBlocked();
        $this->objSpamName->detectNumbers();
        $this->objSpamName->detectBadEmojis();
        $this->objSpamName->detectPunctuation();
        $this->objSpamName->detectCAPITALS();  
        //I don't return false because, I want to send the email anyway but add a spam score to the footer



        return true;
    }

    private function checkHoneyPot()
    {
        $iPotLen = 0;
        $iPotLen = strlen($this->objHoneyPot->getContentsSubmitted()->getValueAsString());

        if ($iPotLen > 0)
        {
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'form Honeypot triggered');
            $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_honeypottriggered', TContactFormController::SPAM_ERRORMESSAGE));//be vague about exact cause
            $this->removeAllFields();
            return false;
        }
            
        return true;
    }

    /**
     * check email subject
     */
    private function checkTopic()
    {
        $sTopic = $this->objTopicInputText->getContentsSubmitted()->getValueAsString();

        //check blocked list
        foreach ($this->arrBlockedTopics as $sBlocked)
        {
            if (stripos($sTopic, $sBlocked) !== false) //if exists
            {
                logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Email subject "'.$sTopic.'" is on blacklist. Entry: "'.$sBlocked.'"');
                $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_emailsubjectonblacklist', TContactFormController::SPAM_ERRORMESSAGE));//be vague about exact cause
                $this->removeAllFields();
                return false;
            }
        }       
        
        //check for spam
        $this->objSpamSubject->setText($sTopic);

        $this->objSpamSubject->detectURLs();
        $this->objSpamSubject->detectBlocked();
        $this->objSpamSubject->detectNumbers();
        $this->objSpamSubject->detectBadEmojis();
        $this->objSpamSubject->detectPunctuation();
        $this->objSpamSubject->detectCAPITALS();  
        $this->objSpamSubject->detectNonLatinCharacterSet(true);  
        //I don't return false because, I want to send the email anyway but add a spam score to the footer


        return true;

    }

    private function checkBody()
    {
        $sBody = '';
        $sBody = $this->objMessage->getContentsSubmitted()->getValueAsString();

        $this->objSpamBody->setText($sBody);

        $this->objSpamBody->detectURLs();
        $this->objSpamBody->detectBlocked();
        $this->objSpamBody->detectNumbers();
        $this->objSpamBody->detectBadEmojis();
        $this->objSpamBody->detectPunctuation();
        $this->objSpamBody->detectCAPITALS();
        $this->objSpamBody->detectNonLatinCharacterSet(true);
        //I don't return false because, I want to send the email anyway but add a spam score to the footer

        return true;
    }

    private function sendEmail()
    {
        $sSubject = '';
        $sEmailAddress = '';
        $sName = '';
        $sBody = '';        
        $iCategoryIndex = 0; //index of array with categories
        
        //==== construct email

        //subject
        if ($this->getCategories()) //can be null
        {
            $iCategoryIndex = (int)$this->objTopicCategoriesSelect->getContentsSubmitted()->getValueAsString();
            $sSubject = $this->getCategories()[$iCategoryIndex].' - ';
        }
        $sSubject.= $this->objTopicInputText->getContentsSubmitted()->getValueAsString();

        if ($this->objSpamName->isSpam(70))
            $sSubject = transs(__CLASS__.'_message_contactform_sendername_spamlikely', '*** SENDERNAME SPAM *** [subject]', 'subject', $sSubject);
        if ($this->objSpamSubject->isSpam(70))
            $sSubject = transs(__CLASS__.'_message_contactform_subject_spamlikely', '*** SUBJECT SPAM *** [subject]', 'subject', $sSubject);            
        if ($this->objSpamBody->isSpam(70))
            $sSubject = transs(__CLASS__.'_message_contactform_body_spamlikely', '*** BODY SPAM *** [subject]', 'subject', $sSubject);

        if (!$this->bHyperLinksAllowed)
            $sSubject = filterURL($sSubject, '[url removed]', true, false); //no tld detect, because that leads sometimes to false positives

        //name
        $sName = $this->objFrom->getContentsSubmitted()->getValueAsString();

        //emailaddress
        $sEmailAddress = filter_var($this->objEmailAddress->getContentsSubmitted()->getValueAsString(), FILTER_SANITIZE_EMAIL);

        //body
        $sBody = strip_tags($this->objMessage->getContentsSubmitted()->getValueAsString());
        if (!$this->bHyperLinksAllowed)
            $sBody = filterURL($sBody, '[url removed]', true, false); //no tld detect, because that leads sometimes to false positives            
        $sBody = nl2br($sBody);
        
        $objSpamName = $this->objSpamName;
        $objSpamSubject = $this->objSpamSubject;
        $objSpamBody = $this->objSpamBody;

            
        $sHTMLContentMain = renderTemplate($this->getPathEmailTemplate(), get_defined_vars());
        $sHTMLEmailTemplateSkin = renderTemplate($this->getPathEmailSkin(), get_defined_vars()); 


        //send email
        $objMail = new TMailSend();
        $objMail->setTo(GLOBAL_EMAIL_ADMIN);
        $objMail->setFrom($sEmailAddress, $sName);
        $objMail->setSubject($sSubject);
        $objMail->setBody($sHTMLEmailTemplateSkin, true);
        if ($objMail->send())
        {
            $this->sendSuccessMessage(transs(__CLASS__.'_message_contactform_emailsentsuccess', 'Message sent successfully')); //not mentioning the word "email" for security reasons
            $this->removeAllFields();
        }
        else
        {
            $this->sendErrorMessage(transs(__CLASS__.'_message_contactform_emailsenterror', 'Sorry, an error occured when sending the message')); //I don't mention the word "email" for security reasons
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Error sending email to "'.$sEmailAddress.'": '.$objMail->getErrorMessage());
            $this->objForm->setSubmittedValuesAsValues();
        }
        unset($objMail);

    }


    private function populateInternal()
    {
        $this->objForm = new FormGenerator('frmContactForm', getURLThisScript());
        $this->objForm->setRecaptchaV3Use($this->bRecaptchaV3Use); //as soon as objForm exists, we can set this. This statement needs to be before submit

        $this->objHoneyPot = new InputText();
        $this->objHoneyPot->setNameAndID('edtFirstName');
        $this->objHoneyPot->setClass('fullwidthtag letitbee');
        $this->objForm->add($this->objHoneyPot, '', transs(__CLASS__.'_contactform_field_honeypot', 'First name'));//this has to have a normal looking field name to prevent raising any description


        $this->objFrom = new InputText();
        $this->objFrom->setNameAndID('edtLastName'); //it is called last name because of the honeypot
        $this->objFrom->setClass('fullwidthtag');
        $this->objFrom->setRequired(true);   
        $this->objFrom->setMaxLength(100);
        $objValidator = new Maximumlength(transs(__CLASS__.'_form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objFrom->addValidator($objValidator);    
        $objValidator = new Required(transs(__CLASS__.'_form_error_requiredfield', 'This is a required field'));
        $this->objFrom->addValidator($objValidator);    
        $this->objForm->add($this->objFrom, '', transs(__CLASS__.'_contactform_field_fromsender', 'Name'));


        $this->objEmailAddress = new InputText();
        $this->objEmailAddress->setNameAndID('edtEmailAddress');
        $this->objEmailAddress->setClass('fullwidthtag');
        $this->objEmailAddress->setRequired(true);   
        $this->objEmailAddress->setMaxLength(100);
        $objValidator = new Maximumlength(transs(__CLASS__.'_form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEmailAddress->addValidator($objValidator);    
        $objValidator = new Required(transs(__CLASS__.'_form_error_requiredfield', 'This is a required field'));
        $this->objEmailAddress->addValidator($objValidator);    
        $objValidator = new Emailaddress(transs(__CLASS__.'_form_error_notavalidemailaddress', 'This is not a valid email address'), true, true);
        $this->objEmailAddress->addValidator($objValidator);          
        $this->objForm->add($this->objEmailAddress, '', transs(__CLASS__.'_contactform_field_emailaddress', 'Email address'));


        $this->objTopicCategoriesSelect = new Select();
        $this->objTopicCategoriesSelect->setNameAndID('optCategory');
        $this->objTopicCategoriesSelect->setClass('fullwidthtag');
        if ($this->arrCategories != null)
        {
            $this->objTopicCategoriesSelect->addOption('', transs(__CLASS__.'_contactform_field_selectionoptiontopic_selectsomething', 'Please select category'), true); 
            $iCountTopics = count($this->arrCategories);
            for ($iIndexCounter = 0;$iIndexCounter < $iCountTopics;++$iIndexCounter)
            {
                $this->objTopicCategoriesSelect->addOption($iIndexCounter, $this->arrCategories[$iIndexCounter]);
            }
            $this->objTopicCategoriesSelect->setSelectedOption('');
            $this->objForm->add($this->objTopicCategoriesSelect, '', transs(__CLASS__.'_contactform_field_category', 'Category'));
        }
        

        $this->objTopicInputText = new InputText();
        $this->objTopicInputText->setNameAndID('edtTopic');
        $this->objTopicInputText->setClass('fullwidthtag');        
        $objValidator = new Maximumlength(transs(__CLASS__.'_form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objTopicInputText->addValidator($objValidator);    
        $objValidator = new Required(transs(__CLASS__.'_form_error_requiredfield', 'This is a required field'));
        $this->objTopicInputText->addValidator($objValidator);    
        //if ($this->arrCategories == null) //only add when no topics in topicsarray
        $this->objForm->add($this->objTopicInputText,'', transs(__CLASS__.'_contactform_field_topic', 'Topic'));


        //populate extra fields from child class
        $this->populate();

        $this->objChkNoLinks = new InputCheckbox();
        $this->objChkNoLinks->setNameAndID('chkUnderstandLinksNotAllowed');
        $this->objChkNoLinks->setValue('1');
        if (!$this->bHyperLinksAllowed)
        {
            $objValidator = new CheckboxChecked(transs(__CLASS__.'_form_error_requiredfield', 'This is a required field'), '1');
            $this->objChkNoLinks->addValidator($objValidator);    
    
            $this->objForm->add($this->objChkNoLinks, '', transs(__CLASS__.'_contactform_field_understandnolinksallowed', 'I understand: I don\'t include hyperlinks because the reader can\'t see (nor click) them.'));           
        }
        

        $this->objMessage = new Textarea();
        $this->objMessage->setNameAndID('txtMessage');
        $this->objMessage->setClass('fullwidthtag');                
        $objValidator = new Required(transs(__CLASS__.'_form_error_requiredfield', 'This is a required field'));
        $this->objMessage->addValidator($objValidator);    
        $this->objForm->add($this->objMessage, '', transs(__CLASS__.'_contactform_field_message', 'Message'));


        if ($this->getRecaptchaV3Use())
            $this->objSubmit = new InputButton();    
        else
            $this->objSubmit = new InputSubmit();    
        $this->objSubmit->setValue(transs(__CLASS__.'_contactform_button_submit', 'Submit'));
        $this->objSubmit->setName('btnSubmit');
        $this->objSubmit->setClass('button_normal');
        $this->objForm->makeRecaptchaV3SubmitButton($this->objSubmit);
        $this->objForm->add($this->objSubmit);        
    }

    /**
     * When the form is
     */
    public function removeAllFields()
    {
        $this->objForm = null;
    }


    /*****************************************
     * 
     *  ABSTRACT FUNCTIONS
     * 
     *****************************************/



    /**
     * populate the form with extra fields not included in the abstract class.
     * this function is called in the private function populateInternal
     * in the parent class.
     * 
     * @return void
     */
    abstract public function populate();



    /**
     * returns a 1d array with categories for the topic-categories <option>-list
     * if you return null, then and editbox is assumed for the topic instead of
     * the <option> list
     * 
     * @return array or null
     */
    abstract public function getCategories();
    

    /**
     * display error message when form not succesfully sent
     *
     * @return string
     */
    abstract public function sendErrorMessage($sError);

    /**
     * display message when successful sent
     *
     * @return string
     */
    abstract public function sendSuccessMessage($sMessage);


    /**
     * path email template
     *
     * @return string
     */
    abstract public function getPathEmailTemplate();

    /**
     * path email template skin
     *
     * @return string
     */
    abstract public function getPathEmailSkin();


}

?>
