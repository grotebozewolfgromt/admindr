<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record
	$iJaar			= date("Y"); 
	$iMaand			= date("m");
	$iDag 			= date("d");
	$iUur			= date("H"); 
	$iMinuut		= date("i");        
	$sNaam			= ''; 
	$sEmail			= '';
	$iUitjesId		= '';
	$iNumberOfStars		= 5;
	$sTitle         	= '';
	$sReviewText         	= '';
        $sIP             	= $_SERVER['REMOTE_ADDR'];
	$bOpWebsite		= true;
	$sPlaats                = '';
        $iCategoryID            = 0;
        
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblUitjesReviews WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row); 
            {
                $iId			= $row["i_id"];
                $sNaam			= $row["s_name"];
                $sEmail			= $row["s_email"];
                $iNumberOfStars		= $row["i_numberofstars"];
                $sTitle         	= $row["s_title"];
                $sReviewText         	= $row["s_reviewtext"];
                $iJaar			= date("Y", $row["i_timestampdatumuitje"]);
                $iMaand			= date("m", $row["i_timestampdatumuitje"]);
                $iDag			= date("d", $row["i_timestampdatumuitje"]);
                $iUur			= date("H", $row["i_timestampdatumuitje"]);
                $iMinuut		= date("i", $row["i_timestampdatumuitje"]);                        
                $sIP             	= $row["s_ip"];
                $bOpWebsite		= $row["b_opwebsite"];                                               
                $sPlaats		= $row["s_plaats"];   
                $iCategoryID            = $row["i_uitjescategoryid"];   
            }
	}
        else
            $_GET['id'] = '';//voor de zekerheid
            

?>


<form name="frmEdit" method=post action="reviewssave.php?id=<?php echo $_GET['id'] ?>">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
   <TR <?php echo getRowColor() ?>> 
      <td>evenement</td>
      <td>                       
          <select name="cbxUitjesID" style="width:250px;">
            <?php
              //uitjes opvragen
              $sSQL = "SELECT * FROM $tblUitjesCategories ORDER BY s_onderwerp";
              $arrResult = mysqliToArray($sSQL);
              foreach ($arrResult as $row)
              {		
                  $iCatID = $row["i_id"];
                  $sCatOnderwerp = $row["s_onderwerp"];


                  if ($iCatID == $iCategoryID)
                    $sSelectedText = 'selected';
                  else
                    $sSelectedText = '';

                  ?><option value="<?php echo $iCatID ?>" <?php echo $sSelectedText?>><?php echo $sCatOnderwerp ?></option><?
              }                  
            ?>
            </select>  
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>naam</td>
      <td> <input name="edtNaam" type="text" id="edtNaam" value="<?php echo $sNaam; ?>" size="100" maxlength="100"> 
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>email</td>
      <td> <input name="edtEmail" type="text" id="edtEmail" value="<?php echo $sEmail; ?>" size="100" maxlength="250"> 
      </td>
    </tr>

    <TR <?php echo getRowColor() ?>> 
      <td>plaats</td>
      <td> <input name="edtPlaats" type="text" id="edtPlaats" value="<?php echo $sPlaats; ?>" size="100" maxlength="100"> 
      </td>
    </tr>    
    <TR <?php echo getRowColor() ?>> 
      <td>sterren</td>
      <td> <input name="edtNumberOfStars" type="text" id="edtNumberOfStars" value="<?php echo $iNumberOfStars; ?>" size="1" maxlength="250"> 1 t/m 5; 1=slecht, 5=goed
      </td>
    </tr>          
    <TR <?php echo getRowColor() ?>> 
      <td>Datum uitje</td>
      <td>
        <?php showDatumSelectBoxen($iDag, $iMaand, $iJaar); ?>
      </td>
    </tr>    
    <TR <?php echo getRowColor() ?>> 
                        <td>review</td>
                        <td><textarea name="edtReviewText" cols="100" rows="10"><?php echo $sReviewText; ?></textarea> 
                        </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>ip adres</td>
      <td> <?php echo $sIP ?> <input type="hidden" name="edtIP" value="<?php echo $sIP ?>">
      </td>
    </tr>  

    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="opwebsite" type="checkbox" id="opwebsite" value="1" <?php echo boolToChecked($bOpWebsite) ?>>	
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
        <input type="button" name="btnAnnuleren" value="annuleren" onclick="window.location.href='reviewsindex.php';"></td>
    </tr>
  </table>
  <br>
</form>

<?php include_once($local_sitemanageradmin."openpage.php"); ?>

