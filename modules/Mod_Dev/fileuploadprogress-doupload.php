<?php
/**
 * controller 
 */


 

    //session started in bootstrap
    include_once '../../bootstrap_cms_auth.php';
    // include_once '../../bootstrap_cms.php';
    

 

    
    $sTargetPath = basename($_FILES["flUpload"]["name"]);



    if (move_uploaded_file($_FILES["flUpload"]["tmp_name"], $sTargetPath))
    {
        echo "The file ". htmlspecialchars( basename( $_FILES["flUpload"]["name"])). " has been uploaded.";
        error_log('file uploaded to '.$_FILES["flUpload"]["name"]);
    } else 
    {
       
        echo "Sorry, there was an error uploading your file.";
        error_log('ERROR uploading file: '.$_FILES["flUpload"]["name"]);

    }
        
 


    /*

    //===fill tabsheets array (only if you want tabsheets)
    $arrTabsheets = $objCurrentModule->getTabsheets(); 
    
    
    //============ RENDER de templates
 
    
    
    $sTitle = transm($sCurrentModule, $sCurrentModule);
    $sHTMLTitle = $sTitle;
    $sHTMLMetaDescription = $sTitle;
    
    
    $sHTMLContentMain = renderTemplate('tpl_fileupload.php', get_defined_vars());

    echo renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php', get_defined_vars());
*/
    
?>