<?php
/**
 * controller 
 */


 

    //session started in bootstrap
    include_once '../../bootstrap_cms_auth.php';
    // include_once '../../bootstrap_cms.php';

 


    error_log('****  fileupload FETCH script executed: fingerprint: '.getFingerprintBrowser());   
    error_log('****  fileupload FETCH script executed: HTTP_ACCEPT: '.$_SERVER['HTTP_ACCEPT']);
    // header("HTTP/1.1 404 Not Found");

    
    $sTargetPath = basename($_FILES["flUploadFetch"]["name"]);



    if (move_uploaded_file($_FILES["flUploadFetch"]["tmp_name"], $sTargetPath))
    {
        echo "The file ". htmlspecialchars( basename( $_FILES["flUploadFetch"]["name"])). " has been uploaded.";
    } else 
    {
       
        echo "Sorry, there was an error uploading your file.";
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