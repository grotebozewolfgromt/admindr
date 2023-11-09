<?php
/**
 * controller index MODULES
 */
    use dr\classes\dom\TPaginator;
    use dr\classes\dom\FormGenerator;
    use dr\classes\dom\validator\Required;
    use dr\classes\dom\tag\Div;
    use dr\classes\dom\tag\form\InputCheckbox;
    use dr\classes\dom\tag\form\InputButton;
    use dr\classes\dom\tag\form\Form;
    use dr\classes\dom\tag\Text;
    use dr\classes\dom\tag\form\Textarea;
    use dr\classes\dom\tag\form\InputSubmit;
    use dr\classes\dom\tag\form\Select;
    use dr\classes\dom\tag\form\Option;

    use dr\classes\models\TSysModules;
    use dr\classes\models\TSysModulesCategories;
    use dr\classes\models\TModel;
    use dr\classes\controllers\TCRUDListController;    
      
    //session started in bootstrap
    include_once '../../bootstrap_cms_auth.php';
 
    
    $objForm = new FormGenerator('htmlgenerator', getURLThisScript());
       
    $sFileNameLastConversion = 'plaintext2html_lastconversion.txt';
    $sFileContents = '';
    if (file_exists($sFileNameLastConversion))
    {
        $arrFileLines = loadFromFile($sFileNameLastConversion);
        $sFileContents = implode("\n", $arrFileLines);
    }
    
    //input
    $objPlainText = new Textarea();
    $objPlainText->setAllowHTML(FILTERXSS_ALLOW_ALL);
    $objPlainText->setNameAndID('edtPlainText');
    $objPlainText->setClass('fullwidthtag');   
    $objPlainText->setRequired(true);   
    $objPlainText->setAutofocus(true);
    $objPlainText->setRows(10);  
    $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
    $objPlainText->addValidator($objValidator);
    if ($objForm->isFormSubmitted())
        $objPlainText->setText($objPlainText->getContentsSubmitted(Form::METHOD_POST)->getValue());
    else
        $objPlainText->setText($sFileContents);
    $objForm->add($objPlainText, '', transm($sCurrentModule, 'plaintext2html_form_field_plaintext', 'Input: plain text'));

    //rel = nofollow
    $objChkNofollow = new InputCheckbox();
    $objChkNofollow->setNameAndID('chkRelNofollow');
    if ($objForm->isFormSubmitted())
        $objChkNofollow->setChecked($objChkNofollow->getContentsSubmitted(Form::METHOD_POST)->getValueAsBool());
    else
        $objChkNofollow->setChecked(false);
    $objForm->add($objChkNofollow, '', transm($sCurrentModule, 'plaintext2html_form_field_relnofollow', 'Links: rel="nofollow"'));

    
    //target = _blank
    $objChkTargetBlank = new InputCheckbox();
    $objChkTargetBlank->setNameAndID('chkTargetBlank');
    if ($objForm->isFormSubmitted())
        $objChkTargetBlank->setChecked($objChkTargetBlank->getContentsSubmitted(Form::METHOD_POST)->getValueAsBool());
    else
        $objChkTargetBlank->setChecked(true);    
    $objForm->add($objChkTargetBlank, '', transm($sCurrentModule, 'plaintext2html_form_field_targetblank', 'Links: target="_blank"'));    

    
    //stop (.) is new line
//    $objChkStopIsNL = new InputCheckbox();
//    $objChkStopIsNL->setNameAndID('chkStopIsNL');
//    if ($objForm->isFormSubmitted())
//        $objChkStopIsNL->setChecked($objChkStopIsNL->getContentsSubmitted(Form::METHOD_POST)->getValueAsBool());
//    else
//        $objChkStopIsNL->setChecked(true);    
//    $objForm->add($objChkStopIsNL, '', transm($sCurrentModule, 'plaintext2html_form_field_stopisnl', 'stop (.) is new line'));        
    
    //submit
    $objSubmit = new InputSubmit();    
    $objSubmit->setValue(transm($sCurrentModule, 'plaintext2html_form_button_convert', 'convert'));
    $objSubmit->setName('btnSubmit');
    $objForm->add($objSubmit, '');    
    
    //output
    $objHTML = new Textarea();
    if ($objForm->isFormSubmitted())
    {
        saveToFile(explode("\n", $objPlainText->getContentsSubmitted(Form::METHOD_POST)->getValue()), $sFileNameLastConversion);
        $sResult = $objPlainText->getContentsSubmitted(Form::METHOD_POST)->getValue();
        
//        if ($objChkStopIsNL->getContentsSubmitted()->getValueAsBool())
//        {
//            //sometimes lines have a nl-char after copy pasting, sometimes not
//            $sResult = str_replace(".\n", '.', $sResult);//converting all existing stops and newlines to only stop (the "bad" ones already don't have a newline after stop)
//            $sResult = str_replace('.', ".\n", $sResult);//all stops are newlines
//        }
        
        $sResult = plainText2HTML(
                $sResult, 
                $objChkTargetBlank->getContentsSubmitted(Form::METHOD_POST)->getValueAsBool(), 
                $objChkNofollow->getContentsSubmitted(Form::METHOD_POST)->getValueAsBool(), 
                );
        $objHTML->setNameAndID('edtHTML');
        $objHTML->setClass('fullwidthtag');   
        $objHTML->setReadOnly(true); 
        $objPlainText->setRows(10);   
        $objHTML->setText($sResult);
        $objForm->add($objHTML, '', transm($sCurrentModule, 'plaintext2html_form_field_html', 'Output: html'));  
        
        
        $objBtnCopyClipboard = new InputButton();
        $objBtnCopyClipboard->setValue(transm($sCurrentModule, 'plaintext2html_form_button_copytoclipboard', 'Copy HTML to clipboard'));
        $objBtnCopyClipboard->setName('btnCopyToClipboard');
        $objBtnCopyClipboard->setOnclick("copyToClipboardEditBox('edtHTML')");
        $objForm->add($objBtnCopyClipboard, '');   
        
        
    }


    
    
    
    
    
    
    
    
    
    
    
    

    //===fill tabsheets array (only if you want tabsheets)
    $arrTabsheets = $objCurrentModule->getTabsheets(); 
    
    
    //============ RENDER de templates
 
    
    
    $sTitle = transm($sCurrentModule, $sCurrentModule);
    $sHTMLTitle = $sTitle;
    $sHTMLMetaDescription = $sTitle;
    
    
    $sHTMLContentMain = renderTemplate('tpl_plaintext2html.php', get_defined_vars());

    echo renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php', get_defined_vars());

    
?>