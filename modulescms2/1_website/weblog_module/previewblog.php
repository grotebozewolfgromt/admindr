<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
/*
	$objTree = new TTree();
    $objTree->setName('[HOOFDCATEGORIE]');
		
	$sSQL = "SELECT * FROM $tblCategories ORDER BY i_order";
	$result = mysql_query($sSQL) or die("<B>".mysql_error()."</B><BR>".$sSQL);
	while ($row = @ mysql_fetch_array($result)) 
	{
		$objChildNode = new TTree();
		$objChildNode->setName($row["s_name"]);
		$objChildNode->setID($row["i_id"]);
		$objChildNode->setAHREF("catdetail.php?id=".$row["i_id"]);		
		$objTree->addChildNodeByParentNodeID($objChildNode, $row["i_parentnodeid"]);
	}	
	*/
?>
<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record
	$iJaar			= date("Y"); 
	$iMaand			= date("m");
	$iDag 			= date("d");
	$iUur			= date("H"); 
	$iMinuut		= date("i");
        
	$bAutoReplanDate        = true;
	$sOnderwerp		= "";
	$sTekst			= "";
	$bOpWebsite		= true;
	$sPlaatjeGroot          = "";
	$sPlaatjeKlein          = "";
	$iAantalKeerBekeken     = 0;
	$bBelangrijk            = false;
	$sAchtergrond           = '';
	$sSourceImage           = '';
	$sYoutubeUrl            = '';
	$sTranscription         = '';
        $sPrettyUrlTitle        = '';
        $iSiteID		= $_SESSION['iSelectedSiteID'];
        $bEOL                   = false;
        $iEOLDate               = 0;
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblWeblog WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row); 
            {
                $iId			= $row["i_id"];
                $iJaar			= date("Y", $row["i_datum"]);
                $iMaand			= date("m", $row["i_datum"]);
                $iDag			= date("d", $row["i_datum"]);
                $iUur			= date("H", $row["i_datum"]);
                $iMinuut		= date("i", $row["i_datum"]);
                $bAutoReplanDate        = $row["b_autoreplandate"];		
                $bOpWebsite		= $row["b_opwebsite"];		
                $sOnderwerp             = $row["s_onderwerp"];
                $sTekst 		= $row["s_tekst"];
                $sPlaatjeGroot          = $row["s_plaatjeurl"];
                $sPlaatjeKlein          = $row["s_plaatjeurlklein"];
                $iParentNodeID          = $row["i_parentnodeid"];
                $iAantalKeerBekeken	= $row["i_aantalkeerbekeken"];	
                $bBelangrijk            = $row["b_belangrijk"];	
                $sSourceImage           = $row["s_sourceimage"];	
                $sYoutubeUrl            = $row["s_youtubeurl"];	
                $sTranscription 	= $row["s_transcription"];
                $sPrettyUrlTitle        = $row["s_prettyurltitle"]; 
                $iSiteID                = $row["i_siteid"]; 
                $bEOL                   = $row["b_eol"]; 
                $iEOLDate               = $row["i_eoldate"]; 
            }
	}
        else
            $_GET['id'] = '';

?>

<h1><?php echo $sOnderwerp; ?></h1>
<?php 
    if (file_exists($local_sitemanagerimages.$sPlaatjeGroot))
    {
        echo '<img src="'.$www_sitemanagerimages.$sPlaatjeGroot.'" width="50%" height="50%"><br><br>';
    }
?>
<?php echo $sTekst; ?>

<br>
<br>
<br>

        <input type="button" name="btnAnnuleren" value="************ TERUG ************" onclick="window.location.href='materiaalindex.php';"></td>

<?php include_once($local_sitemanageradmin."openpage.php"); ?>

