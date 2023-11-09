<?php
//config.php : de configuratiefile van deze module: hier neem je alle gegevens op die specifiek zijn voor deze module
//de variabelen waar INVULLEN achter staat, moet je invullen voor deze module 

//database tabellen definieren:
$tblSitecontent = $sDBTablePrefix."Sitecontent"; 	//INVULLEN!


//deze pagina toevoegen aan de array modules: ($sThisModuleLocalPath ; sThisModuleWwwPath en $sThisModuleCath moeten bekend zijn voordat dit script geinclude wordt)
//==pagina
$sNaamPagina = "Teksten op website"; 	//INVULLEN! 	Dit is de naam van de pagina van deze module. Deze pagina komt in het hoofdmenu te staan: string
$sLinkPagina = "sitecontentindex.php"; 	//INVULLEN! 	De naam van de php-pagina (dus geen path) welke in het menu aan de linker zijde moet komen: string
$iVolgnummer = 1; 						//INVULLEN! 	Dit is het volgordenummer. In welke volgorde komen de pagina's in het hoofmenu te staan ? integer
$iRecht = 3; 							//INVULLEN! 	Dit is het recht. Welk recht moet je minimaal hebben om deze module te kunnen gebruiken ? integer van 1-5 (1 = hoog, 5= laag)
$sThisModuleLocalPathMetLink = $sThisModuleLocalPath.$sLinkPagina;
$sThisModuleWwwPathMetLink = $sThisModuleWwwPath.$sLinkPagina;
$arrModules[] = array($sThisModuleLocalPathMetLink, $sThisModuleWwwPathMetLink, $iVolgnummer, $sNaamPagina, $iRecht, $sThisModuleCath); //opbouw array : opbouw array : localpath van script, wwwpath van script, volgnummer, naam van de module, minimaal dit recht hebben om te kunnen gebruiken, module category


//grootte plaatjes
$sitecontentimagekleinwidth = 400; 		//INVULLEN!		100 = 100 pixels
$sitecontentimagekleinheight = 400; 		//INVULLEN!		100 = 100 pixels
$sitecontentimagekleinquality = 95; 	//INVULLEN!		85 = 85 procent


?>