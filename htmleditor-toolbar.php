<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function createLink() 
{
 
	var str=prompt("Voer pagina url in:\n(bijvoorbeeld : http://www.google.com)", "http://");
  
	if ((str!=null) && (str!="http://")) 
	{
		parent.content.document.execCommand('createlink', '', str);
 	}
}

function insertImage() 
{
 
	var str=prompt("Voer afbeelding url in:\n(bijvoorbeeld : http://www.google.com/image.gif)", "http://");
  
	if ((str!=null) && (str!="http://")) 
	{
		parent.content.document.execCommand('insertimage', '', str);
 	}
}
</script>
<link href="stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#D4D0C8" leftMargin=0 topMargin=0 marginheight="0" marginwidth="0">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td> 
		<select name="select" class="TBGen" id="select" title="Paragraph Format" onChange="parent.content.document.execCommand('formatBlock','',this[this.selectedIndex].value);this.selectedIndex=0; parent.content.htmleditbox.focus();" language="javascript">
			<option selected>Paragraaf</option>
			<option value="&lt;H1&gt;">Heading 1 &lt;H1&gt;</option>
			<option value="&lt;H2&gt;">Heading 2 &lt;H2&gt;</option> 
			<option value="&lt;H3&gt;">Heading 3 &lt;H3&gt;</option> 
			<option value="&lt;H4&gt;">Heading 4 &lt;H4&gt;</option> 
			<option value="&lt;H5&gt;">Heading 5 &lt;H5&gt;</option> 
			<option value="&lt;H6&gt;">Heading 6 &lt;H6&gt;</option> 
			<option value="&lt;PRE&gt;">Formatted &lt;PRE&gt;</option> 
			<option value="removeFormat">Remove All </option>
		</select> 
		<select name="select" class="TBGen" id="select2" title="Font Name"  onChange="parent.content.document.execCommand('fontname','',this[this.selectedIndex].value);this.selectedIndex=0; parent.content.htmleditbox.focus();" language="javascript">
			<option selected>Lettertype</option> 
			<option value="Arial">Arial</option>
			<option value="Times New Roman">Times New Roman</option> 
			<option value="Verdana">Verdana </option>
		</select> 
		<select name="select" class="TBGen" id="select3" title="Font Size"  onChange="parent.content.document.execCommand('fontsize','',this[this.selectedIndex].value);this.selectedIndex=0; parent.content.htmleditbox.focus();" language="javascript">
			<option selected>Grootte</option> 
			<option value="1">1</option> 
			<option value="2">2</option> 
			<option value="3">3</option> 
			<option value="4">4</option> 
			<option value="5">5</option> 
			<option value="6">6</option> 
			<option value="7">7</option>
		</select> 
		<select name="select" class="TBGen" id="select4" title="Font Color" onChange="parent.content.document.execCommand('forecolor', '', this[this.selectedIndex].value);this.selectedIndex=0;parent.content.htmleditbox.focus();" language="javascript">
			<option selected>Kleur </option>
			<option value="red">Rood </option>
			<option value="blue">Blauw </option>
			<option value="green">Groen </option>
			<option value="black">Zwart </option>
		</select> 
		<select name="select" class="TBGen" id="select5" title="Font Back Color" onChange="parent.content.document.execCommand('backcolor', '', this[this.selectedIndex].value);this.selectedIndex=0;parent.content.htmleditbox.focus();" language="javascript">
			<option class="heading" selected>Achtergrondkleur </option>
			<option value="red">Rood </option>
			<option value="blue">Blauw </option>
			<option value="green">Groen </option>
			<option value="black">Zwart </option>
			<option value="yellow">Geel</option> 
			<option value="">Wit</option> 
		</select>
	 </td>
  </tr>
  <tr> 
	<td bgcolor="#808080"><img src="images_cms2/transparantpixel.gif" height="1" width="1"></td>
  </tr>
  <tr> 
	<td bgcolor="#FFFFFF"><img src="images_cms2/transparantpixel.gif" height="1" width="1"></td>
  </tr>
  <tr> 
    <td> 
		<table>
		  <tr> 
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/ok.gif" 		alt="OK : klaar met bewerken"	onClick="parent.textbox.value = parent.content.htmleditbox.innerHTML; parent.close();"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/cancel.gif" 	alt="ANNULEREN : scherm sluiten"	onClick="parent.close();"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/cut.gif" 		alt="knippen"					onClick="parent.content.document.execCommand('cut');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/copy.gif" 	alt="kopi�ren naar klembord"	onClick="parent.content.document.execCommand('copy');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/paste.gif" 	alt="plakken van klembord" 		onClick="parent.content.document.execCommand('paste');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/bold.gif" 	alt="vet drukken" 				onClick="parent.content.document.execCommand('bold');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/italic.gif" 	alt="schuin drukken"			onClick="parent.content.document.execCommand('italic');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/under.gif" 	alt="onderstrepen"				onClick="parent.content.document.execCommand('underline');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/aleft.gif" 	alt="links uitlijnen"			onClick="parent.content.document.execCommand('justifyleft');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/center.gif" 	alt="gecentreerd uitlijnen"		onClick="parent.content.document.execCommand('justifycenter');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/aright.gif" 	alt="rechts uitlijnen"			onClick="parent.content.document.execCommand('justifyright');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/nlist.gif" 	alt="genummerde lijst"			onClick="parent.content.document.execCommand('insertorderedlist');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/blist.gif" 	alt="ongenummerde lijst"		onClick="parent.content.document.execCommand('insertunorderedlist');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/ileft.gif" 	alt="inspringen naar links"		onClick="parent.content.document.execCommand('outdent');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/iright.gif" 	alt="inspringen naar rechts"	onClick="parent.content.document.execCommand('indent');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/wlink.gif" 	alt="weblink naar pagina"		onClick="createLink();"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/image.gif" 	alt="afbeelding invoegen"		onClick="insertImage();"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/undo.gif" 	alt="ongedaan maken typen"		onClick="parent.content.document.execCommand('undo');"></td>
			<td onmouseover="javascript:style.background='#F0ECE4';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/redo.gif" 	alt="opnieuw typen"				onClick="parent.content.document.execCommand('redo');"></td>

		  </tr>
		</table>	
	</td>
  </tr>
  <tr> 
	<td bgcolor="#808080"><img src="images_cms2/transparantpixel.gif" height="1" width="1"></td>
  </tr>
  <tr> 
	<td bgcolor="#FFFFFF"><img src="images_cms2/transparantpixel.gif" height="1" width="1"></td>
  </tr>
  <tr> 
	<td bgcolor="#808080"><img src="images_cms2/transparantpixel.gif" height="2" width="1"></td>
  </tr>

</table></body>
</html>
