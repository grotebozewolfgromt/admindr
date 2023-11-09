<?php
/**
 * Overview module: sys_modules
 */
    use dr\classes\models\TModel;

          
    
    
    
    echo '<h2>'.transm($sCurrentModule, 'csv2table_title', 'CSV to table ').'</h2>';
    echo transm($sCurrentModule, 'csv2table_explanation', 'With this tool you can convert plain text CSV (comma separated values) from Excel for example to html table');
    echo $objForm->generate()->renderHTMLNode();
    if ($objForm->isFormSubmitted())
    {
        echo '<h2>'.transm($sCurrentModule, 'csv2table_result', 'result in HTML:').'</h2>';
        echo $sResult;
    }    