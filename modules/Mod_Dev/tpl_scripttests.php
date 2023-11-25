<?php
/**
 * test
 */

use dr\classes\models\TCMSInvitationCodes;

use dr\classes\patterns\TWeightedScores;
use dr\classes\models\TIPGeoLocation;
use dr\classes\models\TSysCMSPermissionsCountries;
use dr\classes\models\TSysCMSUserAccounts;
use dr\classes\models\TSysCountries;
use dr\classes\patterns\TSpamDetector;
use dr\classes\patterns\TWeightedAverageScore;
use dr\classes\models\TSysContacts;
use dr\classes\types\TCurrency;
use dr\classes\types\TDateTime;
use dr\modules\Mod_Transactions\models\TTransactions;
use dr\modules\Mod_Transactions\models\TTransactionsTypes;

// vardump(isValidEmail('dennis@dennisrenirie.nl', true, true));


include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_security.php');


// $obj = new TTransactionsTypes();
// $obj->install();


$objNum = new TCurrency('6.00', 4);
vardumpdie($objNum);





die();




if (!isset($_POST['edtSpamdetect']))
    $_POST['edtSpamdetect'] = '';
?>
<form method="post">
    <textarea name="edtSpamdetect"><?php echo $_POST['edtSpamdetect']; ?></textarea>
    <input type="submit">
</form>
<pre>
<?php
/*
echo youTubeTimestampMinus('03:50 Instant Doubles
05:40 Slip mode
06:24 Slip mode: Hot Cues
07:26 Slip mode: Jog Wheel
07:50 Slip mode: Loops
08:46 Slip mode: Pause 
09:17 Slip mode: Direction / reverse
09:55 Slip reverse
11:28 Continues play
13:26 Utility menu / CDJ settings
21:58 back side
23:21 CDJ supported file and media formats
25:44 Pro DJ link connection', 30);
*/
?></pre>
<?php


// vardump(75 % 60);

    // filterBadCharsWhiteList('Blatgek bleep)(/^\\');
    // filterBadCharsWhiteList('Blatgek bleep)(/^\\');
// starttest('badchars old');
// for ($i=0; $i < 100; $i++)
//     filterBadCharsWhiteListSlow('Blatgek bleep)(/');
// stoptest('badchars old');

// starttest('badchars1');
// for ($i=0; $i < 100; $i++)
//     filterBadCharsWhiteList('Blatgek bleep)(/');
// stoptest('badchars1');



// echo getDomain($_POST['edtSpamdetect'], true);
// echo getDomain();



// $sSource = 'via-gra, viăgra, cia1is and cia"lis are delicious, also w😄h😄a😄t😄s😄a😄p😄p or s-ms ✉️ me here at 0-1-2-3-4-5-6-7-8 👈✉️';
// $sSource = 'Viagra-Cialis-Levitra ! whoohoo cia1is';
//$sSource = 'whoohoo cia1is';



vardump(obfuscateEmail($_POST['edtSpamdetect']));





// $sSource = "\u{202E}".$_POST['edtSpamdetect'];
$sSource = $_POST['edtSpamdetect'];
// vardump($sSource);



$objSpam = new TSpamDetector($sSource);
// $objSpam->detectURLs();
// $objSpam->detectBlocked();
// $objSpam->detectNumbers();
// $objSpam->detectBadEmojis();
// $objSpam->detectPunctuation();
// $objSpam->detectCAPITALS();
$objSpam->detectNonLatinCharacterSet(false);

echo '<b>spam score: '.$objSpam->getScore().'%</b><br>';
if ($objSpam->isSpam(70))
	echo "you've got spam!<br>";
else
    echo 'no spam detected<br>';


    vardump($objSpam->getLog());

// $objSysCMSUserAccounts = new TSysCMSUserAccounts();
// $objSysCMSUserAccounts->install();


    // for ($iTeller = 0; $iTeller < 100; $iTeller++)
    // {
    //     $objContacts = new TSysContacts();
    //     $objContacts->setCustomIdentifier('cuid'.$iTeller);
    //     $objContacts->setCompanyName('company'.$iTeller);
    //     $objContacts->setFirstNameInitials('first'.$iTeller);
    //     $objContacts->setLastName('last'.$iTeller);
    //     $objContacts->setBillingAddressLine1('line1'.$iTeller);
    //     $objContacts->setBillingAddressLine2('line2'.$iTeller);
    //     $objContacts->setNotes('notes'.$iTeller);
    //     $objContacts->setBillingPostalCodeZip('notes'.$iTeller);
    //     $objContacts->setBillingStateRegion('notes'.$iTeller);
    //     $objContacts->setBillingCity('henktown'.$iTeller);
    //     $objContacts->setBillingCountryID(1);
    //     $objContacts->setBillingVATNumber('vat123');
    //     $objContacts->setBillingEmailAddressDecrypted('email@dexxterclark.com'.$iTeller);
    //     $objContacts->saveToDB();
    // }






// vardump(replaceAccent('dit is een tekst met À en ë'));
// vardump(generatePrettyURLSafeURL('dit is een tekst met À en ë'));
// $sOrg = "Thank you.This was helpful";
// vardump($sOrg);
// vardump(filterURL($sOrg, '[url]', true));







// $objCountries = new TCMSInvitationCodes();
// $objCountries->install();

// echo var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=142.250.179.206')));


// echo '==========<Br>';


// $objGeo = new TIPGeoLocation();
// // $objGeo->setIPAddress('142.250.179.206');
// $objGeo->setIPAddress('31.20.85.155');
// vardump($objGeo->loadFromDB());
// echo $objGeo->getCountryName().'<br>';
// echo $objGeo->getCityName().'<br>';
// echo $objGeo->getLongitude().'<br>';
// echo $objGeo->getLatitude().'<br>';
// echo $objGeo->getTimeZone().'<br>';
// echo $objGeo->getRegionCode().'<br>';
// echo $objGeo->getRegionName().'<br>';
// vardump($objGeo->getCountryCode());

// setcookie('mytestcookie', 'blaat');
// $blaat = 'oep';

// $_SESSION['hoerenjong'] = 'lekker';
// unset($_SESSION['hoerenjong']);
// vardump(issetGETReturn('hoe', 'bloemen'));


// if (issetReturn($blaat) == 'oep1')
// {
//     echo 'exists';
// }
// else
// {
//     echo 'does not exist';
// }

// $_SESSION['blaat'] = issetSessionReturn();
// vardump(issetSessionReturn('blaat'));
// if(issetSessionReturn('blaat') == 1)
// {
//     echo 'variable set';
// }
// else
// {
//     echo 'variable not set';
// }



// $arr=get_defined_vars();
// foreach ($arr as $key=>$val)
// // echo "$key=>$val<br>";
// echo "$key";





