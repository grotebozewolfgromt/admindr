<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	
	
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record
	$sNaam			= "" ;
	$sOmschrijving          = "";
	$sTitel 		= "";
	$sTekst			= "";
	$iSiteID		= $_SESSION['iSelectedSiteID'];
        $sHtmlTitleAlt          = '';
        $sHtmlDescriptionAlt    = '';
        $sTitelAlt              = '';
        $sTekstAlt              = '';
       
	
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblSitecontent WHERE i_id = ".$_GET['id'];
		
            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row); 
            {
                $iId                    = $row["i_id"];
                
                $sPagina		= $row["s_pagina"];
                $sHtmlTitle             = $row["s_htmltitle"];
                $sHtmlTitleAlt          = $row["s_htmltitle_alt"];
                $sHtmlDescription       = $row["s_htmldescription"]; 
                $sHtmlDescriptionAlt    = $row["s_htmldescription_alt"]; 
                $sOmschrijving          = $row["s_omschrijving"];
                $sTitel 		= $row["s_titel"];
                $sTitelAlt		= $row["s_titel_alt"];
                $sTekst			= $row["s_tekst"];
                $sTekstAlt		= $row["s_tekst_alt"];
                $sPlaatjeGroot          = $row["s_plaatjeurl"];
                $sPlaatjeKlein          = $row["s_plaatjeurlklein"];		
                $iSiteID		= $row["i_siteid"];		
            }
	}

?>

<form name="frmEdit" method=post action="sitecontentsave.php?id=<?php echo $_GET['id'] ?>">
  <br>
                    <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td>website</td>
                        <td> 
                            <select name="edtSiteID">
                                <?php 
                                    $arrSites = mysqliToArray("SELECT * FROM $tblWebsites ORDER BY i_id");
                                    foreach ($arrSites as $arrSite)
                                    {
                                            $sSelected = '';
                                            if ($arrSite['i_id'] == $iSiteID)
                                                    $sSelected = ' selected';
                                            echo '<option value="'.$arrSite['i_id'].'"'.$sSelected.'>'.$arrSite['s_domein'].'</option>';
                                    }
                                ?>
                            </select>
                        </td>
                      </tr>                    
                    
                      <tr> 
                        <td bgcolor="#EAEAEA">pagina (inlusief .html extensie)</td>
                        <td bgcolor="#EAEAEA"> 
                          <input name="edtPagina" type="text" id="edtPagina" value="<?php echo $sPagina; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>
                      <tr> 
                        <td>interne omschrijving (niet op website getoond)</td>
                        <td> 
                        <input name="edtOmschrijving" type="text" id="edtOmschrijving" value="<?php echo $sOmschrijving; ?>" size="100" maxlength="250"> 
                   </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#EAEAEA">titel op pagina</td>
                        <td bgcolor="#EAEAEA"> 
                          <input name="edtTitel" type="text" id="edtTitel" value="<?php echo $sTitel; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#EAEAEA">titel op pagina ALTERNATIEF</td>
                        <td bgcolor="#EAEAEA"> 
                          <input name="edtTitelAlt" type="text" id="edtTitelAlt" value="<?php echo $sTitelAlt; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>                      
                      <tr> 
                        <td>html title tag (in html code)</td>
                        <td> 
                          <input name="edtHtmlTitle" type="text" id="edtHtmlTitle" value="<?php echo $sHtmlTitle; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>      
                      <tr> 
                        <td>html title tag ALTERNATIEF</td>
                        <td> 
                          <input name="edtHtmlTitleAlt" type="text" id="edtHtmlTitleAlt" value="<?php echo $sHtmlTitleAlt; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>                           
                      <tr> 
                        <td bgcolor="#EAEAEA">html meta description tag (in html code)</td>
                        <td bgcolor="#EAEAEA"> 
                          <input name="edtHtmlDescription" type="text" id="edtHtmlDescription" value="<?php echo $sHtmlDescription; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>   
                      <tr> 
                        <td bgcolor="#EAEAEA">html meta description tag ALTERNATIEF</td>
                        <td bgcolor="#EAEAEA"> 
                          <input name="edtHtmlDescriptionAlt" type="text" id="edtHtmlDescriptionAlt" value="<?php echo $sHtmlDescriptionAlt; ?>" size="100" maxlength="250"> 
                        </td>
                      </tr>                        
                      <tr> 
                        <td>Tekst op pagina<br> <input name="btnHTMLEditor" type="button" value="html editor" onclick="openHTMLEditor(edtTekst);"> 
                          <br> 
						</td>
                        <td><textarea name="edtTekst" cols="100" rows="10"><?php echo $sTekst; ?></textarea> 
                        </td>
                      </tr>
                      <tr> 
                        <td>Tekst op pagina ALTERNATIEF<br> <input name="btnHTMLEditor2" type="button" value="html editor" onclick="openHTMLEditor(edtTekstAlt);"> 
                          <br> 
						</td>
                        <td><textarea name="edtTekstAlt" cols="100" rows="10"><?php echo $sTekstAlt; ?></textarea> 
                        </td>
                      </tr>                      
         
                      <tr> 
                        <td bgcolor="#EAEAEA">Afbeelding</td>
                        <td bgcolor="#EAEAEA"> <input type="hidden" name="tempplaatjeklein" value=""> 
                          <input type="hidden" name="tempplaatjegroot" value=""> 
                          <input type="hidden" name="plaatjeklein" value="<?php echo $sPlaatjeKlein ?>"> 
                          <input type="hidden" name="plaatjegroot" value="<?php echo $sPlaatjeGroot ?>"> 
                          <input type="hidden" name="plaatjedeleted" value=""> 
                          <SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
		<!--
			function delPic()
			{
				document.frmEdit.itempic.src = '<?php echo $www_sitemanageradminimages."transparantpixel.gif" ?>';
				document.frmEdit.tempplaatjeklein.value = '';
				document.frmEdit.tempplaatjegroot.value = '';
				document.frmEdit.plaatjedeleted.value = '1';	
			}
			
			function showPic() 
			{
				document.frmEdit.itempic.src = picWindow.picdetail.src;
				document.frmEdit.tempplaatjeklein.value = picWindow.frmUpload.tempplaatjeklein.value;
				document.frmEdit.tempplaatjegroot.value = picWindow.frmUpload.tempplaatjegroot.value;
				document.frmEdit.plaatjeklein.value = picWindow.frmUpload.plaatjeklein.value;
				document.frmEdit.plaatjegroot.value = picWindow.frmUpload.plaatjegroot.value;
				picWindow.close();
			}

			function openpicWindow() 
			{
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?php echo $sitecontentimagekleinquality;?>&resizewidth=<?php echo $sitecontentimagekleinwidth;?>&resizeheight=<?php echo $sitecontentimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=600,height=200');
				//mywindow.location.href = 'editpic.php';
				if (picWindow.opener == null)
					 picWindow.opener = self;
			}
		//-->
		</SCRIPT> 
                          <?php 
	  	if (is_file($local_sitemanagerimages.$sPlaatjeGroot))
		{
			?>
                          <a href="javascript:newWindow();"><IMG src="<?php echo $www_sitemanagerimages.$sPlaatjeGroot ?>" border="0" name="itempic"></a><br> 
                          <?php
		}
		else
		{
			?>
                          <a href="javascript:newWindow();"><IMG src="<?php echo $www_sitemanageradminimages."transparantpixel.gif" ?>" border="0" name="itempic"></a><br> 
                          <?php
		}		
		?>
                          <input type="button" name="btnAddImg" value="afbeelding wijzigen/toevoegen" onClick="openpicWindow();"> 
                          <input type="button" name="btnDelImg" value="afbeelding verwijderen" onClick="delPic();"> 
                        </td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                        <td>&nbsp; </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#EAEAEA">&nbsp;</td>
                        <td bgcolor="#EAEAEA">
                            <input type="submit" name="opslaan" value="opslaan"> 
                            <input type="button" name="btnAnnuleren" value="annuleren" onclick="window.location.href='sitecontentindex.php';"></td>
                      </tr>
                    </table>
  <br>
</form>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
