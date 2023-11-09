<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="stylesheet.css" rel="stylesheet" type="text/css">
</head>

<body leftMargin=0 topMargin=0 marginheight="0" marginwidth="0" onLoad="htmleditbox.innerHTML = parent.textbox.value; htmleditbox.focus();" title="type uw tekst. SHIFT ENTER = volgende regel">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" onClick="htmleditbox.focus();">
  <tr bgcolor="#808080">
    <td align="center" valign="middle">
	   <table width="95%" height="95%" border="0" cellspacing="1" cellpadding="0" bgcolor="#000000">
        <tr>
          <td align="left" valign="top" > 
            <table width="100%" height="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
			  <tr>
				<td valign="top" align="left">
				<div id="htmleditbox" name="htmleditbox" contentEditable="true"> 
              	<?
					
					echo $text;
				?>
            	</div>
				</td>
			  </tr>
			</table>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</body>   

</html>
