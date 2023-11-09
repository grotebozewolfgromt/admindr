<?php
/**
 * 3 april php 7 update 
 */

    use dr\classes\models\TSysModules;
    use dr\classes\models\TSysModulesCategories;

    
    include_once(__DIR__.DIRECTORY_SEPARATOR."openpopuppage.php"); 

?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr background="<?php echo $www_sitemanageradminimages ?>sitemanager.jpg"> 
    <td height="136" background="<?php echo $www_sitemanageradminimages ?>sitemanager.jpg" colspan="2" valign="bottom" align="center"> 
            <font size="20" color="red">&nbsp;
                <?php 
                    $arrSites = mysqliToArray("SELECT * FROM $tblWebsites WHERE i_id = ".$_SESSION['iSelectedSiteID']);
                    foreach ($arrSites as $arrSite)
                    {
                        echo $arrSite['s_domein'];
                    }
                ?>
           </font>      
	</td>
  </tr>
  <tr> 
	<td valign="top"> 
	<IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="100" height="10" border="0">
        
	<ul class="leftcolumn_modules">
		<?php

			//display menu with tabsheets from modules
			$arrKeys = array_keys($arrCats);
			foreach($arrKeys as $sCatName)
			{
				$arrMods = $arrCats[$sCatName];
				
				echo '<li>'.$sCatName;
				echo '<ul>';
				
				foreach ($arrMods as $iIndexMod)
				{
					$objSysModulesDB->setRecordPointerToIndex($iIndexMod);

					$sIconPath = GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-module16x16.png';  //default                              
					if (is_file(getPathModuleImages($objSysModulesDB->getNameInternal()).DIRECTORY_SEPARATOR.'icon-module16x16.png'))
						$sIconPath = getURLModuleImages($objSysModulesDB->getNameInternal()).'/icon-module16x16.png';    
					
					$sHTMLSelectedMod = '';                                    
					if ($objSysModulesDB->getNameInternal() == $sCurrentModule)
						$sHTMLSelectedMod = ' class="selected"';
					
					//only display if module folder exists
					if(file_exists(GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$objSysModulesDB->getNameInternal()))
					{                                        
						if (auth($objSysModulesDB->getNameInternal(), AUTH_CATEGORY_MODULEACCESS, AUTH_OPERATION_MODULEACCESS))
						{
							echo '<li'.$sHTMLSelectedMod.'>';
							echo '<img src="'.$sIconPath.'">';
							echo '<a href="'.getURLModule($objSysModulesDB->getNameInternal()).'/index.php">';
							echo transm($objSysModulesDB->getNameInternal(), TRANS_MODULENAME_MENU, $objSysModulesDB->getNameDefault());
							// echo $objSysModulesDB->getNameDefault();
							echo '</a>';              

							//tabsheets of current module
							// if ($sMod == $sCurrentModule)
							// {
							//     $arrTempTabs = $objCurrentModule->getTabsheets();
							//     $arrTabKeys = array();
							//     $arrTabKeys = array_keys($arrTempTabs);

							//     if (count ($arrTempTabs) > 1) //only display if more than one tabsheet (the first one is always the default hook into the cms: index.php of a module)
							//     {
							//         echo '<ul>';
							//         foreach($arrTabKeys as $sTabKey)
							//         {
							//             echo '<li>';
							//             echo '<a href="'.getURLModule($sMod).'/'.$sTabKey.'">';
							//             echo transm($sMod, 'cmsmodulelist_modulename_tabsheet_'.$arrTempTabs[$sTabKey],$arrTempTabs[$sTabKey]);
							//             echo '</a>';
							//             echo '</li>';

							//         }
							//         echo '</ul>';
							//     }
							// }

							echo '</li>';  
						}
					}
				}                                
				
				echo '</ul>';
				echo '</li>';
			}  
			unset($arrCats);
		?>
	</ul>       
<?php
	


	//CMS 2 categorien weergeven en hun modules
	for($iTeller=0;$iTeller < count($arrModuleCaths);$iTeller++)
	{
		?>
		  <!-- ================= <?php echo $arrModuleCaths[$iTeller];?> ===================================== -->
		  <table width="100%" border="0" bgcolor="#000000" cellpadding="1" cellspacing="1">
			<tr> 
			  <td bgcolor="#CCCCCC"> 
				<table width="100%" border="0" cellpadding="0">
				  <tr> 
					<td><?php echo str_replace("_", "&nbsp;", $arrModuleCaths[$iTeller]); //underscores vervangen door spaties?></td>
				  </tr>
				</table>
			  </td>
			</tr>
		  </table>
		<?php
		//================modules van deze categorie weergeven
			
		//array van modules (=2 dimensionale array: $arrModules) sorteren op volgordenummer
		//TODO

		//array van modules doorlopen en weergeven  --> opbouw array : localpath van script, wwwpath van script, volgnummer, naam van de module, minimaal dit recht hebben om te kunnen gebruiken, category
		for($iTeller3=0;$iTeller3 < count($arrModules);$iTeller3++)
		{
			$arrCurrModule = $arrModules[$iTeller3];//1 item uit de modulearray pakken
			
			if ($arrCurrModule[5] == $arrModuleCaths[$iTeller]) //goede categorie ?
			{
//			var_dump($_SESSION);
//                        die();
                            if (($_SESSION['iAdminUserLevel'] <= $arrCurrModule[4]) && (isset($_SESSION['iAdminUserLevel']))) //heb je wel voldoende rechten ?
                        
					echo "&nbsp;<A href=\"".$arrCurrModule[1]."?titelsub=".$arrCurrModule[3]."\"><img border=\"0\" src=\"".$www_sitemanageradminimages."item_icon.gif\"></A>&nbsp;<A href=\"".$arrCurrModule[1]."?titelsub=".$arrCurrModule[3]."\">".str_replace(" ", "&nbsp;", $arrCurrModule[3])."</A><BR>\n";
			}
		}
		?>
		<!-- EINDE ================= <?php echo $arrModuleCaths[$iTeller];?> ===================================== -->
	  <BR>
	  <BR>
		<?
	}
	
?>
	<br>
	  <table width="100%" border="0" bgcolor="#000000" cellpadding="1" cellspacing="1">
		<tr> 
		  <td bgcolor="#CCCCCC"> 
			<table width="100%" border="0" cellpadding="0">
			  <tr> 
				<td>geselecteerde site</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>	
        
        <?php
            $sSelected = '';
            $objWebsites->resetRecordPointer();
            while($objWebsites->next())
            {
                if ($objWebsites->getID() == GLOBAL_WEBSITEID_SELECTEDINCMS)                                      
                    $sSelected = ' class="selected"';
                else
                    $sSelected = '';
                ?>
                    <a href="<?php echo addVariableToURL(getURLCMSDashboard(), GETARRAYKEY_SELECTEDSITEID, $objWebsites->getID());?>"><?php echo $objWebsites->getWebsiteName();?></a>
                <?php
            }
        ?>

	

  </td>
    <td width="100%" align="center" valign="top">
	 	<!-- TABEL VOOR DE WERKELIJKE SITE -->
    	<IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="100" height="10" border="0">
		<TABLE border="0" bgcolor="#000000" cellspacing="1" cellpadding="0" width="97%" bccolor="#333333">
		  <TR bgcolor="#CCCCCC">
			<TD>
				<TABLE border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
					<TR>
						<TD widht="99%" align ="left">&nbsp;<?php 
                                                if (isset($titelsub))
                                                    echo $titelsub;
                                                ?>&nbsp;</TD>
						<TD width="1%" align ="right"><!--<a href="javascript:LogOut();"><img src="<?php echo $www_sitemanageradminimages ?>x.gif" border="0"></A>--></TD>
					</TR>
				</TABLE>
			</TD>
		  </TR>
		  <TR>
			<TD bgcolor="#F5F5F5">
				<TABLE border="0" cellpadding="10" cellspacing="0" width="100%">
					<TR>
						<TD>

