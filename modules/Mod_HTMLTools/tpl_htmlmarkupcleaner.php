<?php
/**
 * Overview module: sys_modules
 */
    use dr\classes\models\TModel;

          
    
    
    
    echo '<h2>'.transm($sCurrentModule, 'htmlmarkupcleaner_title', 'HTML markup cleaner').'</h2>';
    echo transm($sCurrentModule, 'htmlmarkupcleaner_explanation', 'Some tools like browser HTML generators and word processors can generate so much poop that interferes with the CSS of a site.<br>This tools cleans the HTML for you.<br>Finally some well deserved rest at night!');
    echo $objForm->generate()->renderHTMLNode();
    if ($objForm->isFormSubmitted())
    {
        echo '<h2>'.transm($sCurrentModule, 'htmlmarkupcleaner_result', 'Clean HTML').'</h2>';
        echo $sResult;
    }    