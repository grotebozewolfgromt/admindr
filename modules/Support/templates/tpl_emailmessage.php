<?php echo $sBody; ?><br>
<br>
================================================================<br>
This message was sent via a contactform on: <?php echo GLOBAL_PATH_DOMAIN ?><br>
================================================================<br>
Subject: <?php echo $sSubject; ?><br>
Name: <?php echo $sName; ?><br>
Email address:<?php echo $sEmailAddress; ?><br>
IP address: <?php echo $_SERVER['REMOTE_ADDR']; ?><br>
<br>
================================================================<br>
Spam Report<br>
================================================================<br>
Spam score name: <?php echo $objSpamName->getScore() ?>&percnt;<br>
<?php 
    if ($objSpamName->getLog())
        echo '&nbsp;&nbsp;&nbsp;'.implode('<br>&nbsp;&nbsp;&nbsp;',$objSpamName->getLog()).'<br>';
?>
Spam score subject: <?php echo $objSpamSubject->getScore() ?>&percnt;<br>
<?php 
    if ($objSpamSubject->getLog())
        echo '&nbsp;&nbsp;&nbsp;'.implode('<br>&nbsp;&nbsp;&nbsp;',$objSpamSubject->getLog()).'<br>';
?>
Spam score body: <?php echo $objSpamBody->getScore() ?>&percnt;<br>
<?php 
    if ($objSpamBody->getLog())
        echo '&nbsp;&nbsp;&nbsp;'.implode('<br>&nbsp;&nbsp;&nbsp;',$objSpamBody->getLog()).'<br>';
?>
