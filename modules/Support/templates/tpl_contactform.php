<?php
    // vardump($sHoer);
    if ($objForm) //can be null after submitted or on spam
        echo $objForm->generate()->renderHTMLNode();
?>
