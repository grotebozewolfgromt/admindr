<?php


    ini_set('mysql.connect_timeout', 1200); //1200 seconden wachten voor timeout

  include_once("../../../config.php");
  include_once("../../../library_std.php"); 


 function displayLinks($arrBacklinks)
  {
      echo '<table>';
      echo '<tr>';
      echo '<td><b>id</b></td>';
      echo '<td><b>linksectie domein</b></td>';
      echo '<td><b>check</b></td>';
      echo '<td><b>op site</b></td>';
      echo '<td><b>last check</b></td>';
      echo '<td><b>last change</b></td>';
      echo '<td><b>link</b></td>';
      echo '<td><b>omschrijving</b></td>';
      echo '<td><b>backlink from</b></td>';
      echo '<td><b>linkt naar ons domein</b></td>';
      echo '</tr>';

      foreach ($arrBacklinks as $arrBacklink)
      {
          echo '<tr>';
          echo '<td>'.$arrBacklink['i_id'].'</td>';
          echo '<td>'.$arrBacklink['s_domein'].'</td>';
          echo '<td>'.$arrBacklink['b_checkbacklink'].'</td>';
          echo '<td>'.$arrBacklink['b_opwebsite'].'</td>';
          echo '<td>'.date('d-m-Y',$arrBacklink['i_datelastcheck']).'</td>';
          echo '<td>'.date('d-m-Y',$arrBacklink['i_datechanged']).'</td>';
          echo '<td><a href="'.$arrBacklink['s_link'].'" target="_blank">'.$arrBacklink['s_link'].'</td>';
          echo '<td>'.$arrBacklink['s_omschrijving'].'</td>';
          echo '<td><a href="'.$arrBacklink['s_backlink'].'" target="_blank">'.$arrBacklink['s_backlink'].'</td>';
          echo '<td>'.$arrBacklink['s_linktnaaronsdomein'].'</td>';
          echo '</tr>';
      }
      echo '</table>';
  }

  
  
  $link = mysql_connect($sqlhost, $sqluser, $sqlpassword) or die("Could not connect<br>");
  mysql_select_db($sqldatabase) or die("Could not select database<br>");
  
  

  $sOutput = '';
  $sTempOutput = '';
  ob_start(); //start recording

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Backlinks check alle websites</title>
</head>
<body>
    <h1>Checking backlinks <?php echo date('d-m-Y H:m') ?></h1>
    Links in database:
	<?php

        $iOneWeekAgo = time() - (60 * 60 * 24 * 7);//--> 7 dagen
        $iOneMonthAgo = time() - (60 * 60 * 24 * 30);//--> 30 dagen

//        $arrBacklinks = MySQLToArray("SELECT $tblWeblinks.*, $tblWebsites.s_domein FROM $tblWeblinks, $tblWebsites WHERE $tblWebsites.i_id = $tblWeblinks.i_siteid AND b_checkbacklink = 1 AND i_datelastcheck < $iOneMonthAgo AND i_datechanged < $iOneMonthAgo AND b_opwebsite = 1 AND s_linktnaaronsdomein != '' ORDER BY i_volgorde ASC");
        
                 
        echo 'alle links in database:';
        $arrBacklinks = MySQLToArray("SELECT $tblWeblinks.*, $tblWebsites.s_domein FROM $tblWeblinks, $tblWebsites WHERE $tblWebsites.i_id = $tblWeblinks.i_siteid ORDER BY $tblWebsites.i_id, $tblWeblinks.i_id ASC");        
        displayLinks($arrBacklinks);
               
        echo '<br>';
        echo '<br>';
        echo '<br>';
        
        //alleen de eerste 10 elementen pakken anders duurt het script te lang
        $arrBacklinks = MySQLToArray("SELECT $tblWeblinks.*, $tblWebsites.s_domein FROM $tblWeblinks, $tblWebsites "
                . "WHERE $tblWebsites.i_id = $tblWeblinks.i_siteid AND b_checkbacklink = 1 "
                . "AND b_opwebsite = 1 "
                . "AND b_opwebsite = 1 "
                . "AND s_backlink != '' "
                . "AND s_linktnaaronsdomein != '' "
                . "AND i_datelastcheck < $iOneMonthAgo "
                . "AND i_datechanged < $iOneWeekAgo "
                . "ORDER BY $tblWebsites.i_id, $tblWeblinks.i_id ASC");        
        $arrBacklinks = array_slice($arrBacklinks, 0,10); 

        echo 'alle links:<br>';
        echo '-zichtbaar op site<br>';
        echo '-moet backlink checken<br>';
        echo '-backlink mag NIET leeg zijn<br>';
        echo '-de link-naar-ons-domein mag NIET leeg zijn<br>';
        echo '-laatste check langer dan een maand geleden<br>';
        echo '-laatste wijziging langer dan een week geleden (ze hebben 7 dagen om de link te plaatsen op hun website)<br>';
        echo '-alleen de eerste 10 links (anders duurt het te lang):<br>';        
        echo 'de volgende websites worden gecheckt:<br>';         //eerst de domeinen weergeven die gecheckt gaan worden: zo kun je achterhalen waar het steeds fout gaat als dit script zn nek breekt
        displayLinks($arrBacklinks);
        
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo 'de check gaat nu van start:<br>';
                
        foreach ($arrBacklinks as $arrRecord)
        {
            echo 'check nu <a href="'.$arrRecord['s_backlink'].'" target="_blank">'.$arrRecord['s_backlink'].'</a> op tekst "'.$arrRecord['s_linktnaaronsdomein'].'": ';
            
            $ctx = stream_context_create(array('http' => array( 'timeout' => 10  )));  /* na 10 seconden een timeout */ 
            $sContentsPage = file_get_contents($arrRecord['s_backlink'], false, $ctx); 
            

            
            if ($sContentsPage)
            {
                //kijken of de link wel voor komt
                if (strpos($sContentsPage, $arrRecord['s_linktnaaronsdomein']) !== false)    //letterlijk vergelijk
                {
                    echo 'link bestaat';
                    
                                       
                    
                    //update record in db
                    $arrVar = array('i_datelastcheck');
                    $arrVal = array(time());                
                    changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRecord['i_id']);   
                    
                    
                    //check of ie niet naar hetzelfde domein linkt
                    echo ' (check A-B B-A link: "'.$arrRecord['s_domein'].'" - "'.$arrRecord['s_linktnaaronsdomein'].'")';
                    if (strpos($arrRecord['s_domein'], $arrRecord['s_linktnaaronsdomein']) !== false)
                    {
                        echo ' <b>'.$arrRecord['s_domein'].'(A) link naar '.$arrRecord['s_backlink'].'(B) en linkt terug naar '.$arrRecord['s_linktnaaronsdomein'].'(A) NOFOLLOW!</b>';
                        
                        $arrVar = array('b_nofollow', 's_opmerkingen');
                        $arrVal = array('1', "\nlinkcheck ".  date('d-m-Y').' nofollow, linkt terug ');                
                        changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRecord['i_id']);                           
                    }
                }
                else
                {
                    echo '<b>link bestaat NIET, nofollow</b>';
                    
            
                    //update record in db
                    $arrVar = array('s_opmerkingen', 'i_datelastcheck', 'b_nofollow');
                    $arrVal = array("\nlinkcheck ".  date('d-m-Y').' : link does not exist nofollow', time(), '1');                
                    changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRecord['i_id']);                       
                    
                }
            }
            else
            {   
                echo '<b>no content on page</b>';
                
                //update record in db
                $arrVar = array('s_opmerkingen', 'i_datelastcheck');
                $arrVal = array("\nlinkcheck ".  date('d-m-Y').' :  no content ', time());                
                changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRecord['i_id']);                   
                
            }
            
             
            
            
            echo '<br>';
        }
        
        if (!$arrBacklinks)
            echo ' === nothing to check. geen links die aan bovenstaande condities voldoen. ===<br>';        
        
        /*
        $arrBacklinks = array_slice($arrBacklinks, 0,10); //alleen de eerste 10 elementen pakken anders duurt het script te lang
        
        
        if (!$arrBacklinks)
        {
            echo 'no links to check!!<br>';
        }
            
        //eerst de domeinen weergeven die gecheckt gaan worden: zo kun je achterhalen waar het steeds fout gaat als dit script zn nek breekt
        echo'de volgende websites worden gecheckt:<br>';
        foreach ($arrBacklinks as $arrRow)
        {       
            echo $arrRow['s_backlink'].'<br>';
        }        
        echo '<br><br>de check gaat nu van start:<br>';
        
        
        
        foreach ($arrBacklinks as $arrRow)
        {       
            mysql_ping();

            
//            $sBacklinkContents = httpgetpost($arrRow['s_backlink']);
            
            $ctx = stream_context_create(array( 
                'http' => array( 
                    'timeout' => 10  //--> na 10 seconden een timeout
                    
                    ) 
                ) 
            );             
            $sBacklinkContents = file_get_contents($arrRow['s_backlink'], false, $ctx);

            if (($sBacklinkContents == '') || ($arrRow['s_linktnaaronsdomein'] == ''))
            {
                if ($sBacklinkContents == '')
                {
                    echo '<b>ATTENTION!! LINK ID '.$arrRow['i_id'].': no data received from: '.$arrRow["s_backlink"].'</b>.<a href="materiaaldetail.php?id='.$arrRow["i_id"].'" target="_blank">edit</a><br>';

                    $arrVar = array('s_opmerkingen');
                    $arrVal = array("\nlinkcheck ".  date('d-m-Y').' : geen antwoord van server, misschien timeout? ');                
                    changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRow['i_id']);                 
                }
                
                if($arrRow['s_linktnaaronsdomein'] == '')
                {
                    echo '<b>ATTENTION!! LINK ID '.$arrRow['i_id'].': link naar ons domein is leeg </b>.<a href="materiaaldetail.php?id='.$arrRow["i_id"].'" target="_blank">edit</a><br>';

                    $arrVar = array('s_opmerkingen');
                    $arrVal = array("\nlinkcheck ".  date('d-m-Y').' : link naar ons domein is leeg ');                
                    changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRow['i_id']);                 
                    
                }
            }
            else
            {
                //var_dump($sBacklinkContents);
                mysql_ping();


                //kijken of de link wel voor komt
                if (!strpos($sBacklinkContents, $arrRow['s_linktnaaronsdomein']))
                {


                  //laatste check bijwerken
                  $arrVar = array('b_opwebsite','i_datelastcheck', 's_opmerkingen');
                  $arrVal = array('0',time(), "\nlinkcheck ".  date('d-m-Y').' : linkt NIET naar ons domein '.$arrRow['s_linktnaaronsdomein']);                
                  changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRow['i_id']);              


                  echo '<b>ATTENTION!! LINK ID '.$arrRow['i_id'].' DOES NOT LINK TO '.$arrRow['s_linktnaaronsdomein'].';</b> backlink: <a href="'.$arrRow["s_backlink"].'" target="_blank">'.$arrRow["s_link"].'</a> (ouder dan een week). LINK NIET OP WEBSITE<a href="materiaaldetail.php?id='.$arrRow["i_id"].'" target="_blank">edit</a><br>';


                }
                else // als wel voorkomt
                {
                    //nofollow als deze naar dit domein verwijst
                    if ($arrRow['b_nofollow'] == 0)
                    {
                        if (strpos($sBacklinkContents, $arrRow['s_domein']))
                        {
                            echo '<b>LINK ID '.$arrRow['i_id'].' LINKS TO THIS DOMAIN ('.$arrRow['s_domein'].'):</b> <a href="'.$arrRow["s_backlink"].'" target="_blank">'.$arrRow["s_link"].'</a> nofollow attribute inserted. <a href="materiaaldetail.php?id='.$arrRow["i_id"].'" target="_blank">edit</a><br>';

                            $arrVar = array('b_nofollow', 's_opmerkingen');
                            $arrVal = array('1', "\nlinkcheck ".  date('d-m-Y').' : linkt direct terug naar '.$arrRow['s_domein'].' daarom nofollow geplaatst');                
                            changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRow['i_id']);                 
                        }     
                    }


                  $arrVar = array('i_datelastcheck');
                  $arrVal = array(time());                
                  changeRecord($arrVar, $arrVal, $tblWeblinks, 'i_id', $arrRow['i_id']);              

                  echo $arrRow["s_backlink"].' = ok, backlink still exists<br>';
                }
            }
            

            $sTempOutput = ob_get_clean(); //stop recording
//            echo $sTempOutput;
            $sOutput.= $sTempOutput;
            ob_start(); //start recording
        }
      */
    ?>
    
    
    -- end of checks --              
</body>
</html>
<?

  $sOutput.= ob_get_clean(); //stop recording
  echo $sOutput;

  //echo $sOutput; //output op scherm
  sendemail($sAdminEmailadres, '', '', 'backlinks check '.date('d-m-Y'), $sOutput, 'noreply'); //output mailen
  
?>