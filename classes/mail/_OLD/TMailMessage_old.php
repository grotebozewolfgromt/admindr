<?php
namespace dr\classes\mail;

use dr\classes\mime\TMimeMessage;
use dr\classes\mime\TMimePart;
use dr\classes\mime\TMime;
use dr\classes\mime\TMimeAbstract;

/**
 * Description of TMailMessage
 *
  * 
 * this class represents an email message
 * 
 * 
 * 13 feb 2013: TMailMessage: created. 
 * 1 mrt 2014: TMailMessage: added addListUnsubscribe()  TMailMessage: added addListUnsubscribe() en getListUnsubscribe
 * 1 mrt 2014: TMailMessage: added getSafeListUnsubscribeField()
 * 
 * 14 dec 2020: vervangen door wrapper voor PHP mailer
 * 
 * @todo rekening houden dat er niet midden in een html tag geknipt wordt --> transformStringIntoLines70Chars()
 * 
 * @author drenirie
 */
class TMailMessage_OLD
{   
    private $arrProhibitHeader = array('to', 'cc', 'bcc', 'from', 'subject', 'reply-to', 'return-path', 'date', 'message-id'); //prohibit these keywords from the header, because you can do nasty spamming stuff with them when not used properly    
    private $sSubject = '';
    private $sFrom = '';
    private $sReplyTo = '';
    private $arrTo = null;
    private $arrCC = null;
    private $arrBCC = null;
    private $sMessageIDHeader = '';
    private $objMimeMessage = null;
    private $arrListUnsubscribe = null; //spam protection --> GMail shows 'unsubscribe' button. This header tells where to redirect de the unsubscribe action (url)

 
    
    public function  __construct()
    {
       $this->objMimeMessage = new TMimeMessage();
    }

    public function __destruct()
    {
       unset($this->objMimeMessage);
    }
    

    /**
     * set the transfer encoding such as Base 64
     * 
     * @param string $sEncoding
     */
    public function setContentTransferEncoding($sEncoding = TMime::ENCODING_BASE64)
    {
        $this->getMimeMessage()->setContentTransferEncoding($this->getSafeHeaderField($sEncoding));
    }
    
    public function getContentTransferEncoding()
    {
        $this->getMimeMessage()->getContentTransferEncoding();        
    }
    
    public function setCharset($sCharset = CHARSET_UTF8)
    {
        $this->getMimeMessage()->setCharset($this->getSafeHeaderField($sCharset));
    }
    
    public function getCharset()
    {
        return $this->getMimeMessage()->getCharset();
    }
    
    /**
     * 
     * protected omdat geen hond er wat mee te maken heeft, en ook om bijvoorbeeld header injection te voorkomen (bijvoorbeeld bij attachments toevoegen
     * 
     * @return TMimeMessage
     */
    protected function getMimeMessage()
    {
        return $this->objMimeMessage;
    }
    
    /**
     * set message that you email client can't read the mail and they have to visit your site for example
     * 
     * @param string $sMessage
     */
    public function setMessageNotSupported($sMessage)
    {
        $this->getMimeMessage()->setMessageNotSupported($sMessage);
    }
    
    /**
     * get the current message that you email client doesnt support the layout of this mail
     * 
     * @return string
     */
    public function getMessageNotSupported()
    {
        return $this->getMimeMessage()->getMessageNotSupported();
    }
    
    /**
     * set the messageid (mailheader)
     * 
     * @param string $sID
     */
    public function setMessageID($sID)
    {
        $this->sMessageIDHeader = $sID;
    }
    
    /**
     * return message id (mailheader)
     * 
     * @return string
     */
    public function getMessageID()
    {
        return $this->sMessageIDHeader;
    }
    
    /**
     * adding plain text for the email
     * 
     * @param string $sHTML
     * @param string $sCharset
     * @param string $sContentTransferEncoding for example TMime::ENCODING_BASE64
     */
    public function addBodyPlainText($sPlainText, $sCharset = CHARSET_UTF8, $sContentTransferEncoding = '')
    {
        $objMimePart = new TMimePart();
        $objMimePart->setContentType(MIME_TYPE_TEXT);
        $objMimePart->setContent($sPlainText);
        $objMimePart->setCharset($this->getSafeHeaderField($sCharset)); //preventing header injection by filtering the field
        $objMimePart->setContentTransferEncoding($this->getSafeHeaderField($sContentTransferEncoding)); //preventing header injection by filtering the field    
        
        $this->getMimeMessage()->addPart($objMimePart);        
    }

    
    /**
     * adding HTML text for the email
     * 
     * @param string $sHTML
     * @param string $sCharset
     * @param string $sContentTransferEncoding for example TMime::ENCODING_BASE64
     */
    public function addBodyHTML($sHTML, $sCharset = CHARSET_UTF8, $sContentTransferEncoding = '')
    {
        $objMimePart = new TMimePart();
        $objMimePart->setContentType(MIME_TYPE_HTML);
        $objMimePart->setContent($sHTML); 
        $objMimePart->setCharset($this->getSafeHeaderField($sCharset)); //preventing header injection by filtering the field
        $objMimePart->setContentTransferEncoding($this->getSafeHeaderField($sContentTransferEncoding)); //preventing header injection by filtering the field
              
        $this->getMimeMessage()->addPart($objMimePart);        
    }
    
    /**
     * adding an attachment to the email
     * 
     * @param string $sFilepath path of the file that you want to attach
     * @param type $bInlineAttachment TRUE = inline attachment (bijv. afbeelding midden in de tekst), FALSE = regular attachment (een standaard bijgevoegde attachment)
     * @return boolean attachment succesfully added ?
     */
    public function addAttachmentFile($sFilepath, $bInlineAttachment = false)
    {
        if (file_exists($sFilepath))
        {
            $this->addAttachment(file_get_contents($sFilepath), extractFileFromPath($sFilepath),  filectime($sFilepath), filemtime($sFilepath), fileatime($sFilepath), $bInlineAttachment);
            return true;
        }
        else
        {
            error('file not found ("'.$sFilepath.'"): file not attached');
            return false;
        }
    }


    /**
     * add attachement
     * @param type $sFileContents inhoud van het bestand
     * @param type $sFilename de naam van de file in de email bijvoorbeeld 'panther.jpg'
     * @param type $bInlineAttachment TRUE = inline attachment (bijv. afbeelding midden in de tekst), FALSE = regular attachment (een standaard bijgevoegde attachment)
     */
    protected function addAttachment($sFileContents, $sFilename, $iCreationDateTimeStamp = 0, $iModificationDateTimeStamp = 0, $iAccessDateTimeStamp = 0, $bInlineAttachment = false)
    {
        $objMimePart = new TMimePart();
        $objMimePart->setContentType(MIME_TYPE_OCTETSTREAM);
        $objMimePart->setContentTransferEncoding(TMime::ENCODING_BASE64);

        if ($bInlineAttachment)
            $objMimePart->setContentDisposition(TMime::DISPOSITION_INLINE);
        else
            $objMimePart->setContentDisposition(TMime::DISPOSITION_ATTACHMENT);
        $objMimePart->setFilename($this->getSafeHeaderField($sFilename)); //user specified so it could be potentially dangerous
        $objMimePart->setContent($sFileContents);
        $objMimePart->setCreationDateAttachment($iCreationDateTimeStamp);
        $objMimePart->setModificationDateAttachment($iModificationDateTimeStamp);
        $objMimePart->setReadDateAttachment($iAccessDateTimeStamp);
        
        $this->getMimeMessage()->addPart($objMimePart);                
    }


    
    /**
     * sets the senders email address
     * 
     * @param string $sEmailaddress
     * @param string $sName
     */
    public function setFrom($sEmailaddress, $sName = '')
    {

vardump($this->formatAddress($sEmailaddress, $sName), 'hoeple')        ;
        $this->sFrom = $this->formatAddress($sEmailaddress, $sName);
    }

    /**
     * get the sender of the email
     * 
     * @return string
     */
    public function getFrom()
    {
        return $this->sFrom;
    }


    /**
     * adds a receipient
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function addTo($sEmailAddress, $sName)
    {
        $this->arrTo[] = $this->formatAddress($sEmailAddress, $sName);
    }
    
    /**
     * returns the To: array
     * 
     * @return array
     */
    public function getTo()
    {
        return $this->arrTo;
    }

    /**
     * adds a Carbon Copy receipient
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function addCC($sEmailAddress, $sName = '')
    {
        $this->arrCC[] = $this->formatAddress($sEmailAddress, $sName);
    }
    
    /**
     * get the CC array
     * 
     * @return array
     */
    public function getCC()
    {
        return $this->arrCC;
    }

    /**
     * adds a Blank Carbon Copy receipient
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function addBCC($sEmailAddress, $sName = '')
    {
                
        $this->arrBCC[] = $this->formatAddress($sEmailAddress, $sName);
        
    }

    /**
     * get the BCC array
     * 
     * @return array
     */
    public function getBCC()
    {
        return $this->arrBCC;
    }
    
    /**
     * sets the reply-to emailaddress
     * 
     * @param string $sEmailAddress
     * @param string $sName
     */
    public function setReplyTo($sEmailAddress, $sName = '')
    {
        $this->sReplyTo = $this->formatAddress($sEmailAddress, $sName);
    }

    /**
     * return reply-to 
     * 
     * @return string
     */
    public function getReplyTo()
    {
        return $this->sReplyTo;
    }
    
    /**
     * sets the subject of the email in plain text
     * 
     * @param string $sSubject
     */
    public function setSubject($sSubject)
    {
        $this->sSubject = $sSubject;
    }
    
    /**
     * return subject of the email
     * 
     * @return string
     */
    public function getSubject()
    {
        return $this->sSubject;
    }
    
    /**
     * add a url to List-Unsubscribe header
     * this header tell the email client wich url leads to unsubscription of a newsletter
     * Gmail shows a 'unsubscribe' button when this header is found
     * 
     * @param string $sUrl (this can be a mail adress, specify it by using 'mailto:'
     */
    public function addListUnsubscribe($sUrl)
    {
        $this->arrListUnsubscribe[] = $sUrl;
    }
    
    /**
     * returns List-Unsubscribe: array
     * 
     * @return array
     */
    public function getListUnsubscribe()
    {
        return $this->arrListUnsubscribe;
    }
    
    /**
     * opbreken van de body in regels van 70 karakters
     * de emailstandaard schrijft voor dat de regels in een emailbericht maar 70 karakters lang mogen zijn
     * tevens worden de unix \n vervangen door de voorgeschreven \r\n voor emails --> deze functie is dus ongevoelig voor het door elkaar gebruiken van windows en unix EOL's
     * 
     * RFC 2822 (Internet Message Format): SHOULD be no more than 78 characters, excluding the CRLF.
     
     * @todo rekening houden dat er niet midden in een html tag geknipt wordt
     */
    protected function transformStringIntoLines70Chars($sMessageBody)
    {
        //string naar array (op unix EOL's)
        $arrBodyLines = explode("\n", $sMessageBody);
        
        
        for ($iTeller = 0; $iTeller < count($arrBodyLines); $iTeller++)
        {
            //filter array voor windows EOL's (zo hebben we uniform allemaal unix EOL's)
            $arrBodyLines[$iTeller] = str_replace("\r", '',$arrBodyLines[$iTeller]);
            
            //gebroken regels van max 70 karakters
            $arrBodyLines[$iTeller] = wordwrap($arrBodyLines[$iTeller], TMime::LINELENGTH, "\n", true);
        }
             
        //flatten array 
        $sFlatArray = implode("\n", $arrBodyLines);
        
        //convert unix EOL into windows EOL's ( \r\n)
        return str_replace("\n", "\r\n", $sFlatArray);
    }      
   
    
    /**
     * to prevent email injection: filter emailadres
     * 
     * @param string $sText
     * @return string
     */
    protected function getSafeEmailAddress($sText)
    {
        /*
        //banned header directions:
        foreach($this->arrProhibitHeader as $sProhibit)
            $sText = str_ireplace ( $sProhibit , '' , $sText);           
        
        $arrBannedChars = array("\r" => '',
                      "\n" => '',
                      "\t" => '',
                      '"'  => '',
                      ','  => '',
                      '<'  => '',
                      '>'  => '',
                      '\0'  => '',
                      '\x0B'  => '',            
                      ':'  => '',   //--> important one!         
        );

        //filter
        $sFiltered = trim(strtr($sText, $arrBannedChars));
                  
        //if non printable characters, erase all
        if (!ctype_print($sFiltered))
            $sFiltered = '';

        //check if emailaddress is correct
        if (!isValidEmail($sFiltered, false))
            $sFiltered = '';

            vardump($sFiltered, 'blueblap')        ;              
        */        

        return filter_var($sText, FILTER_VALIDATE_EMAIL);     
    }
    
    /**
     * to prevent injection: Filter name (afzendernaam bijvoorbeeld)
     *
     * @param string $sText
     * @return string
     */
    protected function getSafeName($sText)
    {
        //banned header directions:
        foreach($this->arrProhibitHeader as $sProhibit)
            $sText = str_ireplace ( $sProhibit , '' , $sText);        
        
        $arrBannedChars = array("\r" => '',
                      "\n" => '',
                      "\t" => '',
                      '"'  => "'",
                      '<'  => '[',
                      '>'  => ']',
                      '\0'  => '',
                      '\x0B'  => '',               
                      ':'  => '',      //--> important one!                   
        );
        
        //filter
        $sFiltered = trim(strtr($sText, $arrBannedChars));
        
        //if non printable characters, erase all
        if (!ctype_print($sFiltered))
            $sFiltered = '';
        
        return $sFiltered;
    }    
    
    /**
     * to prevent injection: Filter of other data
     *
     * 
     * @param string $sText
     * @return string
     */
    protected function getSafeHeaderField($sText)
    {
        //banned header directions:
        foreach($this->arrProhibitHeader as $sProhibit)
            $sText = str_ireplace ( $sProhibit , '' , $sText);

        $arrBannedChars = array("\r" => '',
                      "\n" => '',
                      "\t" => '',
                      '\0'  => '',
                      '\x0B'  => '',         
                      ':'  => '',  //--> important one!     maybe we missed one                 
        );


        return strtr($sText, $arrBannedChars);
    }    
    
    /**
     * to prevent injection: Filter of ListUnsubscribe header
     * This filtering is not as strict as as the other filtering fields, for example the colon (:) and brackets are allowed
     * 
     * @param string $sText
     * @return string
     */
    
    protected function getSafeListUnsubscribeField($sText)
    {
        $arrBannedChars = array("\r" => '',
                      "\n" => '',
                      "\t" => '',
                      '\0'  => '',
                      '\x0B'  => '',         
        );


        return strtr($sText, $arrBannedChars);
    }       
   
    /**
     * format an email address
     * 
     * returns $sEmailaddress if there is no name
     * if there is a name it will return: $sName <$sEmailaddress>;
     *
     * @param string $sEmailaddress
     * @param string $sName
     * @return string
     */
    private function formatAddress($sEmailaddress, $sName = '')
    {
        if ($sName)
            return $sName.' <'.$sEmailaddress.'>';
        else
            return $sEmailaddress;

    }

}

?>
