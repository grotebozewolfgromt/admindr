<? include_once("openpopuppage.php"); ?>
<script language="JavaScript">
function GetHtml() 
{
	return htmleditbox.innerHTML;
}

function createLink() 
{
 
	var str=prompt("Voer pagina url in:", "http://");
  
	if ((str!=null) && (str!="http://")) 
	{
		document.execCommand('CreateLink', '', str);
 	}
}
</script>
<table width="100%" height="100%" border="0">
  <tr> 
    <td align="center" valign="middle">
	  <table width="90%" border="0">
        <tr> 
          <td bgcolor="#000000">
		    <table width="100%" height="100%" border="0" cellpadding="5" bgcolor="#EAEAEA">
              <tr> 
                <td align="left" valign="top"> <select name="select" class="TBGen" id="select" title="Paragraph Format" onChange="formatC('formatBlock',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                    <option class="heading" selected>Paragraaf 
                    <option value="&lt;H1&gt;">Heading 1 &lt;H1&gt; 
                    <option value="&lt;H2&gt;">Heading 2 &lt;H2&gt; 
                    <option value="&lt;H3&gt;">Heading 3 &lt;H3&gt; 
                    <option value="&lt;H4&gt;">Heading 4 &lt;H4&gt; 
                    <option value="&lt;H5&gt;">Heading 5 &lt;H5&gt; 
                    <option value="&lt;H6&gt;">Heading 6 &lt;H6&gt; 
                    <option value="&lt;PRE&gt;">Formatted &lt;PRE&gt; 
                    <option value="removeFormat">Remove All </select> <select name="select" class="TBGen" id="select2" title="Font Name" onChange="formatC('fontname',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                    <option class="heading" selected>Lettertype 
                    <option value="Arial">Arial 
                    <option value="Arial Black">Arial Black 
                    <option value="Arial Narrow">Arial Narrow 
                    <option value="Comic Sans MS">Comic Sans MS 
                    <option value="Courier New">Courier New 
                    <option value="System">System 
                    <option value="Times New Roman">Times New Roman 
                    <option value="Verdana">Verdana 
                    <option value="Wingdings">Wingdings </select> <select name="select" class="TBGen" id="select3" title="Font Size" onChange="formatC('fontsize',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                    <option class="heading" selected>Grootte 
                    <option value="1">1 
                    <option value="2">2 
                    <option value="3">3 
                    <option value="4">4 
                    <option value="5">5 
                    <option value="6">6 
                    <option value="7">7 </select> <select name="select" class="TBGen" id="select4" title="Font Color" onChange="formatC('forecolor',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                    <option class="heading" selected>Kleur 
                    <option value="red">Rood 
                    <option value="blue">Blauw 
                    <option value="green">Groen 
                    <option value="black">Zwart </select> <select name="select" class="TBGen" id="select5" title="Font Back Color" onChange="formatC('backcolor',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                    <option class="heading" selected>Achtergrondkleur 
                    <option value="red">Rood 
                    <option value="blue">Blauw 
                    <option value="green">Groen 
                    <option value="black">Zwart 
                    <option value="yellow">Geel 
                    <option value="">Wit 
                  </select>
                  <br>
                  <table>
                    <tr> 
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/cut.gif" 	alt="knippen"					onClick="document.execCommand('cut');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/copy.gif" 	alt="kopi�ren naar klembord"	onClick="document.execCommand('copy');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/paste.gif" 	alt="plakken van klembord" 		onClick="document.execCommand('paste');htmleditbox.focus();"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/bold.gif" 	alt="vet drukken" 				onClick="document.execCommand('bold');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/italic.gif" alt="schuin drukken"			onClick="document.execCommand('italic');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/under.gif" 	alt="onderstrepen"				onClick="document.execCommand('underline');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/aleft.gif" 	alt="links uitlijnen"			onClick="document.execCommand('justifyleft');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/center.gif" alt="gecentreerd uitlijnen"		onClick="document.execCommand('justifycenter');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/aright.gif" alt="rechts uitlijnen"			onClick="document.execCommand('justifyright');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/nlist.gif" 	alt="genummerde lijst"			onClick="document.execCommand('insertorderedlist');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/blist.gif" 	alt="ongenummerde lijst"		onClick="document.execCommand('insertunorderedlist');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/ileft.gif" 	alt="inspringen naar links"		onClick="document.execCommand('outdent');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/iright.gif" alt="inspringen naar rechts"	onClick="document.execCommand('indent');"></td>
                      <td onmouseover="javascript:style.background='#CCCCCC';style.cursor='hand';" onmouseout="javascript:style.background='';window.status = '';"><img src="images_cms2/wlink.gif" 	alt="weblink naar pagina"		onClick="createLink();"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td align="left" valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
                    <tr> 
                      <td bgcolor="#000000"> 
					    <table width="100%" height="50" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                          <tr> 
                            <td align="left" valign="top"> 
                            <div id="htmleditbox" name="htmleditbox" contentEditable="true">
<?
							echo $text;
?>							
							</div>
							</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td align="left" valign="top"><input type="button" name="btnOk" value="ok, tekst gebruiken" onClick="opener.returnHTML()"> 
                  <input type="button" name="btnAnnuleren" value="annuleren" onClick="self.close();"> 
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<? include_once("closepopuppage.php"); ?>
