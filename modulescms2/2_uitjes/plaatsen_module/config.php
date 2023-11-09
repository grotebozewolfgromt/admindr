<?php
//config.php : de configuratiefile van deze module: hier neem je alle gegevens op die specifiek zijn voor deze module
//de variabelen waar INVULLEN achter staat, moet je invullen voor deze module 


//database tabellen definieren:
$tblPlaatsen = $sDBTablePrefix."Plaatsen";//INVULLEN!

//deze pagina toevoegen aan de array modules: ($sThisModuleLocalPath ; sThisModuleWwwPath en $sThisModuleCath moeten bekend zijn voordat dit script geinclude wordt)

//==pagina
$sNaamPagina = "Plaatsen"; 				//INVULLEN! 	Dit is de naam van de pagina van deze module. Deze pagina komt in het hoofdmenu te staan: string
$sLinkPagina = "materiaalindex.php"; 		//INVULLEN! 	De naam van de php-pagina (dus geen path) welke in het menu aan de linker zijde moet komen: string
$iVolgnummer = 1; 						//INVULLEN! 	Dit is het volgordenummer. In welke volgorde komen de pagina's in het hoofmenu te staan ? integer
$iRecht = 2; 							//INVULLEN! 	Dit is het recht. Welk recht moet je minimaal hebben om deze module te kunnen gebruiken ? integer van 1-5 (1 = hoog, 5= laag)
$sThisModuleLocalPathMetLink = $sThisModuleLocalPath.$sLinkPagina;
$sThisModuleWwwPathMetLink = $sThisModuleWwwPath.$sLinkPagina;
$arrModules[] = array($sThisModuleLocalPathMetLink, $sThisModuleWwwPathMetLink, $iVolgnummer, $sNaamPagina, $iRecht, $sThisModuleCath); //opbouw array : opbouw array : localpath van script, wwwpath van script, volgnummer, naam van de module, minimaal dit recht hebben om te kunnen gebruiken, module category

//grootte plaatjes
$plaatsenimagekleinwidth = 300; //100 = 100 pixels//INVULLEN!
$plaatsenimagekleinheight = 300; //100 = 100 pixels//INVULLEN!
$plaatsenimagekleinquality = 75; //85 = 85 procent//INVULLEN!
$plaatsenimagegrootwidth = 1200; //100 = 100 pixels//INVULLEN!
$plaatsenimagegrootheight = 1200; //100 = 100 pixels//INVULLEN!
$plaatsenimagegrootquality = 80; //85 = 85 procent//INVULLEN!

//include xml feedmodule
//include_once("rssfeed.class.php");



?>