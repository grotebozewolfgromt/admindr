<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iPlaatsenId			= ""; //is het een nieuw record
	$iUitjesId			= ""; //is het een nieuw record

	$sTekst		= "";
	$sPlaatjeGroot	= "";
	$sPlaatjeKlein 	= "";
        $bOpWebsite     = 0;
  
        if (is_numeric($_GET['plaatsenid']) && is_numeric($_GET['uitjesid']))
	{
            $sSQL = "SELECT $tblPlaatsenUitjes.*, $tblPlaatsen.s_plaats, $tblUitjes.s_onderwerp FROM $tblPlaatsenUitjes, $tblPlaatsen, $tblUitjes WHERE i_plaatsenid = ".$_GET['plaatsenid']." AND i_uitjesid = ".$_GET['uitjesid']." AND $tblPlaatsenUitjes.i_plaatsenid = $tblPlaatsen.i_id AND $tblPlaatsenUitjes.i_uitjesid = $tblUitjes.i_id";

            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row);
            {
                $sPlaats        = $row["s_plaats"];
                $sUitje         = $row["s_onderwerp"];

                $sPlaatjeGroot 	= $row["s_plaatjegroot"];
                $sPlaatjeKlein  = $row["s_plaatjeklein"];
                $sTekst         = $row["s_tekst"];      
                $bOpWebsite     = $row["b_opwebsite"];      
            }
	}
        else
        {
            $_GET['plaatsenid'] = '';//voor de zekerheid
            $_GET['uitjesid'] = '';//voor de zekerheid
        }

?>


<form name="frmEdit" method=post action="plaatsenuitjessave.php?plaatsenid=<?php echo $_GET['plaatsenid'] ?>&uitjesid=<?php echo $_GET['uitjesid'] ?>">
<input type="hidden" name="prettyurlplaats" value="<?php echo generatePrettyURLSafeURL($sPlaats); ?>">
<input type="hidden" name="prettyurluitje" value="<?php echo generatePrettyURLSafeURL($sUitje); ?>">    
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
    <TR <?php echo getRowColor() ?>> 
                        <td>evenement<br>
						</td>
                        <td>
                            <?php echo $sUitje; ?>
                        </td>
                        
    </tr> 
    <TR <?php echo getRowColor() ?>> 
                        <td>plaats<br>
						</td>
                        <td>
                            <?php echo $sPlaats; ?>
                        </td>
                        
    </tr> 
    <TR <?php echo getRowColor() ?>> 
                        <td>info uitje en plaats (html)<br>
						</td>
                        <td>
                            <textarea name="edtTekst" cols="100" rows="10"><?php echo $sTekst; ?></textarea> 
                        </td>
                        
    </tr>     
    <TR <?php echo getRowColor() ?>> 
      <td>Afbeelding</td>
      <td> 
        <input type="hidden" name="tempplaatjeklein" value="">
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
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?php echo $uitjesplaatsenimagekleinquality;?>&resizewidth=<?php echo $uitjesplaatsenimagekleinwidth;?>&resizeheight=<?php echo $uitjesplaatsenimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=800,height=600');
				//mywindow.location.href = 'editpic.php';
				if (picWindow.opener == null)
					 picWindow.opener = self;
			}
		//-->
		</SCRIPT>
        <?php 
	  	if (is_file($local_sitemanagerimages.$sPlaatjeKlein))
		{
			?>
			<a href="javascript:newWindow();"><IMG src="<?php echo $www_sitemanagerimages.$sPlaatjeKlein ?>" border="0" name="itempic"></a><br>
			<?
		}
		else
		{
			?>
			<a href="javascript:newWindow();"><IMG src="<?php echo $www_sitemanageradminimages."transparantpixel.gif" ?>" border="0" name="itempic"></a><br>
			<?
		}		
		?>
                          
                            <input type="button" name="btnAddImg" value="afbeelding wijzigen/toevoegen" onClick="openpicWindow();"> 
                            <input type="button" name="btnDelImg" value="afbeelding verwijderen" onClick="delPic();"> 
                        </td>
    </tr>
   <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="chkOpWebsite" type="checkbox" id="chkOpWebsite" value="1" <?php echo boolToChecked($bOpWebsite) ?>>	
        Weergeven op website
	  </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
        <input type="submit" name="opslaan" value="opslaan">
        <input type="button" name="btnAnnuleren" value="annuleren" onclick="window.location.href='plaatsenuitjesindex.php';"></td>        
    </tr>
  </table>
  <br>
</form>

		      	
    </tbody>
</table>


<?php include_once($local_sitemanageradmin."openpage.php"); ?>

