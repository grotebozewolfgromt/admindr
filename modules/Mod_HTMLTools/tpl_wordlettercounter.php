<?php
/**
 * Overview module: sys_modules
 */
    use dr\classes\models\TModel;

          
    
    
    
    echo '<h2>'.transm($sCurrentModule, 'wordlettercounter_title', 'word and letter counter').'</h2>';
    echo transm($sCurrentModule, 'wordlettercounter_explanation', 'This tool counts words, letters and characters.');
    echo $objForm->generate()->renderHTMLNode();
    if ($objForm->isFormSubmitted())
    {
        echo '<h2>'.transm($sCurrentModule, 'wordlettercounter_result', 'Results').'</h2>';
        echo $sResult;
    }    