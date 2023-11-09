<?php
//config.php : de configuratiefile van deze module: hier neem je alle gegevens op die specifiek zijn voor deze module
//de variabelen waar INVULLEN achter staat, moet je invullen voor deze module 


//database tabellen definieren:
$tblWeblog = $sDBTablePrefix."Weblog";//INVULLEN!
$tblWeblogTags = $sDBTablePrefix."WeblogTags";//INVULLEN!

//deze pagina toevoegen aan de array modules: ($sThisModuleLocalPath ; sThisModuleWwwPath en $sThisModuleCath moeten bekend zijn voordat dit script geinclude wordt)

//==pagina
$sNaamPagina = "Weblog"; 				//INVULLEN! 	Dit is de naam van de pagina van deze module. Deze pagina komt in het hoofdmenu te staan: string
$sLinkPagina = "materiaalindex.php"; 		//INVULLEN! 	De naam van de php-pagina (dus geen path) welke in het menu aan de linker zijde moet komen: string
$iVolgnummer = 1; 						//INVULLEN! 	Dit is het volgordenummer. In welke volgorde komen de pagina's in het hoofmenu te staan ? integer
$iRecht = 2; 							//INVULLEN! 	Dit is het recht. Welk recht moet je minimaal hebben om deze module te kunnen gebruiken ? integer van 1-5 (1 = hoog, 5= laag)
$sThisModuleLocalPathMetLink = $sThisModuleLocalPath.$sLinkPagina;
$sThisModuleWwwPathMetLink = $sThisModuleWwwPath.$sLinkPagina;
$arrModules[] = array($sThisModuleLocalPathMetLink, $sThisModuleWwwPathMetLink, $iVolgnummer, $sNaamPagina, $iRecht, $sThisModuleCath); //opbouw array : opbouw array : localpath van script, wwwpath van script, volgnummer, naam van de module, minimaal dit recht hebben om te kunnen gebruiken, module category

//grootte plaatjes
$weblogimagekleinwidth = 960; //100 = 100 pixels//INVULLEN!
$weblogimagekleinheight = 540; //100 = 100 pixels//INVULLEN!
$weblogimagekleinquality = 85; //85 = 85 procent//INVULLEN!
$weblogimagegrootwidth = 1920; //100 = 100 pixels//INVULLEN!
$weblogimagegrootheight = 1080; //100 = 100 pixels//INVULLEN!
$weblogimagegrootquality = 90; //85 = 85 procent//INVULLEN!

//include xml feedmodule
//include_once("rssfeed.class.php");


function createrssfeedweblog($sXMLFilePath)
{
	global $tblWeblog;
	global $sTitelSite;
	global $www_url;
	global $www_images;
	global $www_sitemanagerimages;
	global $local_sitemanagerimages;

	$objFeed = new RSSFeed();
	$objFeed->setTitle('Weblog Beter Evenementen Quiz Experts');
	$objFeed->setDescription("Weblog Beter Evenementen Quiz Experts : $www_url");
	$objFeed->setLink($www_url);

	// get your news items from somewhere, e.g. your database: 
	$sSql = "SELECT * FROM $tblWeblog WHERE b_opwebsite = 1 AND i_datum < ".time()." ORDER BY i_volgorde DESC LIMIT 0,10";
	$result = mysql_query($sSql) or die("<B>".mysql_error()."</B><BR>".$query);
	while ($row = @ mysql_fetch_array($result)) 
	{
		if ($row["b_opwebsite"])
		{
			if (is_file($local_sitemanagerimages.$row["s_plaatjeurlklein"]))
				$sDescription = "<img src=\"$www_sitemanagerimages".$row["s_plaatjeurlklein"]."\" vspace=\"3\" hspace=\"3\" align=\"left\" alt=\"".$row["s_onderwerp"]."\">".$row["s_tekst"]; //plaatje toevoegen aan nieuwsbericht
			else
				$sDescription = $row["s_tekst"];

			//footer toevoegen
			$sDescription = $sDescription.'<br><br><br>';
			$sDescription = $sDescription.'Bron : <a href="'.$www_url.'weblog/'.$row["i_id"].'/'.generatePrettyURLSafeURL($row["s_onderwerp"]).'.html">'.$www_url.'materiaal/'.$row["i_id"].'/'.generatePrettyURLSafeURL($row["s_onderwerp"]).'.html</a><br>';
			$sDescription = $sDescription.'Reageren ? <a href="'.$www_url.'weblog/'.$row["i_id"].'/'.generatePrettyURLSafeURL($row["s_onderwerp"]).'.html#reageer">Klik hier!</a><br>';
			$sDescription = $sDescription.'Nieuwe teksten automatisch per email ? <a href="'.$www_url.'abonnement.html">Neem een abonnement!</a><br>';
			

				
			$objItem = new RSSItem();
			$objItem->setTitle($row["s_onderwerp"]);
			$objItem->setDescription($sDescription);
			$objItem->setLink($www_url.'weblog/'.$row["i_id"].'/'.generatePrettyURLSafeURL($row["s_onderwerp"]).'.html');
                        $objItem->setPubDate($row["i_datum"]);
			$objItem->setGuid($objItem->getLink());
			 
			$objFeed->addItem($objItem);
		}
	} 

	$objFeed-> saveToFile($sXMLFilePath, "RSS2.0");	

}

?>