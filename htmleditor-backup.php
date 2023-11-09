<?php include_once("openpopuppage.php"); ?>
<table width="100%" height="100%" border="0">
  <tr> 
    <td align="center" valign="middle"><table width="90%" border="0">
        <tr> 
          <td bgcolor="#000000"><table width="100%" border="0" bgcolor="#EAEAEA">
              <tr> 
                <td align="center" valign="middle"><form name=composeForm method=post action="htmleditorsendvalues.php" onSubmit="return false;">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td> <input type=hidden name=tipo value=edit> <input type=hidden name=is_html value="true"> 
        <input type=hidden name=sid value="CB0C921E4745FF667EE6109E6F07BC3A"> 
        <input type=hidden name=lid value="1"> <input type=hidden name=folder value="inbox"> 
        <input type=hidden name=sig value=""> <table width="100%" border=0 cellspacing=1 cellpadding=0 >
          <tr> 
            <td class="default"> <script language="JavaScript">
bLoad=false;
pureText=true;
bodyTag="<BODY MONOSPACE STYLE=\"font:10pt verdana\">";
bTextMode=false;
public_description=new Editor;

/*****************************
 Power Editor class
 member function:
 SetHtml
 GetHtml
 SetText
 GetText
 GetCompFocus()
 *****************************/
function Editor() {
	this.put_html=SetHtml;
	this.get_html=GetHtml;
	this.put_text=SetText;
	this.get_text=GetText;
	this.CompFocus=GetCompFocus;
}
function GetCompFocus() {
	Composition.focus();
}

function GetText() {
	return Composition.document.body.innerText;
}

function SetText(text) {
	text = text.replace(/\n/g, "<br>")
	Composition.document.body.innerHTML=text;
}

function GetHtml() {
	if (bTextMode) 
		return Composition.document.body.innerText;
	else {
		cleanHtml();
		cleanHtml();
		return Composition.document.body.innerHTML;
	}
}

function SetHtml(sVal) {
	if (bTextMode) Composition.document.body.innerText=sVal;
	else Composition.document.body.innerHTML=sVal;
}
//End  of Editor Class

/***********************************************
 Initialize everything when the document is ready
 ***********************************************/
var YInitialized = false;
function document.onreadystatechange(){
	if (YInitialized) return;
	YInitialized = true;
	var i, s, curr;
	// Find all the toolbars and initialize them.
	for (i=0; i<document.body.all.length; i++) {
		curr=document.body.all[i];
		if (curr.className == "Btn" && !InitBtn(curr))
			alert("Toolbar: " + curr.id + " failed to initialize. Status: false");
	}
	Composition.document.open()
	Composition.document.write(bodyTag);
	Composition.document.close()
	Composition.document.designMode="On"
	public_description.put_html(hiddencomposeForm.hiddencomposeFormTextArea.value);

}

/***********************************************
 Initialize a button ontop of toolbar
 ***********************************************/
function InitBtn(btn) {
	btn.onmouseover = BtnMouseOver;
	btn.onmouseout = BtnMouseOut;
	btn.onmousedown = BtnMouseDown;
	btn.onmouseup = BtnMouseUp;
	btn.ondragstart = YCancelEvent;
	btn.onselectstart = YCancelEvent;
	btn.onselect = YCancelEvent;
	btn.YUSERONCLICK = btn.onclick;
	btn.onclick = YCancelEvent;
	btn.YINITIALIZED = true;
	return true;
}

// Hander that simply cancels an event
function YCancelEvent() {
	event.returnValue=false;
	event.cancelBubble=true;
	return false;
}

// Toolbar button onmouseover handler
function BtnMouseOver() {
	if (event.srcElement.tagName != "IMG") return false;
	var image = event.srcElement;
	var element = image.parentElement;
	// Change button look based on current state of image.- we don't actually have chaned image
	// could be commented but don't remove for future extension
	if (image.className == "Ico") element.className = "BtnMouseOverUp";
	else if (image.className == "IcoDown") element.className = "BtnMouseOverDown";
	event.cancelBubble = true;
}

// Toolbar button onmouseout handler
function BtnMouseOut() {
	if (event.srcElement.tagName != "IMG") {
		event.cancelBubble = true;
		return false;
	}
	var image = event.srcElement;
	var element = image.parentElement;
	yRaisedElement = null;
	element.className = "Btn";
	image.className = "Ico";
	event.cancelBubble = true;
}

// Toolbar button onmousedown handler
function BtnMouseDown() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    event.returnValue=false;
    return false;
  }
  var image = event.srcElement;
  var element = image.parentElement;

  element.className = "BtnMouseOverDown";
  image.className = "IcoDown";

  event.cancelBubble = true;
  event.returnValue=false;
  return false;
}

// Toolbar button onmouseup handler
function BtnMouseUp() {
  if (event.srcElement.tagName != "IMG") {
    event.cancelBubble = true;
    return false;
  }

  var image = event.srcElement;
  var element = image.parentElement;

  if (element.YUSERONCLICK) eval(element.YUSERONCLICK + "anonymous()");

  element.className = "BtnMouseOverUp";
  image.className = "Ico";

  event.cancelBubble = true;
  return false;
}



// Check if toolbar is being used when in text mode
function validateMode() {
  if (! bTextMode) return true;
  alert("Vink de optie : \"Bekijk HTML broncode\" uit om de taakbalk te gebruiken");
  Composition.focus();
  return false;
}

function sendHtml(){
	if(bTextMode){
		document.composeForm.body.value = public_description.get_text();
		return true;
	}
	else{
		document.composeForm.body.value = public_description.get_html();
		return true;
	}
}

//Formats text in composition.
function formatC(what,opt) {
  if (!validateMode()) return;
  if (opt=="removeFormat") {
    what=opt;
    opt=null;
  }
  if (opt==null) Composition.document.execCommand(what);
  else Composition.document.execCommand(what,"",opt);
  pureText = false;
  Composition.focus();
}

//Switches between text and html mode.
function setMode(newMode) {
  bTextMode = newMode;
  var cont;
  if (bTextMode) {
    cleanHtml();
    cleanHtml();
    cont=Composition.document.body.innerHTML;
    Composition.document.body.innerText=cont;
  } else {
    cont=Composition.document.body.innerText;
    Composition.document.body.innerHTML=cont;
  }
  
  Composition.focus();
}

//Finds and returns an element.
function getEl(sTag,start) {
  while ((start!=null) && (start.tagName!=sTag)) start = start.parentElement;
  return start;
}

function createLink() {
  if (!validateMode()) return;
  
  var isA = getEl("A",Composition.document.selection.createRange().parentElement());
  var str=prompt("Enter url:", isA ? isA.href : "http:\/\/");
  
  if ((str!=null) && (str!="http://")) {
    if (Composition.document.selection.type=="None") {
      var sel=Composition.document.selection.createRange();
      sel.pasteHTML("<A HREF=\""+str+"\">"+str+"</A> ");
      sel.select();
    }
    else formatC("CreateLink",str);
  }
  else Composition.focus();
}

//Sets the text color.
function foreColor() {
  if (! validateMode()) return;
  var arr = showModalDialog("/ym/ColorSelect?3", "", "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:35em");
  if (arr != null) formatC('forecolor', arr);
  else Composition.focus();
}

//Sets the background color.
function backColor() {
  if (!validateMode()) return;
  var arr = showModalDialog("/ym/ColorSelect?3", "", "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:35em");
  if (arr != null) formatC('backcolor', arr);
  else Composition.focus()
}



function cleanHtml() {
  var fonts = Composition.document.body.all.tags("FONT");
  var curr;
  for (var i = fonts.length - 1; i >= 0; i--) {
    curr = fonts[i];
    if (curr.style.backgroundColor == "#ffffff") curr.outerHTML = curr.innerHTML;
  }
}

function getPureHtml() {
  var str = "";
  var paras = Composition.document.body.all.tags("P");
  if (paras.length > 0) {
    for (var i=paras.length-1; i >= 0; i--) str = paras[i].innerHTML + "\n" + str;
  } else {
    str = Composition.document.body.innerHTML;
  }
  return str;
}

</script> <table cellpadding="3" cellspacing="0" border="0">
                <tr> 
                  <td> <div class="yToolbar" id="ParaToolbar"> 
                      <div class="TBHandle"> </div>
                      <select name="select" class="TBGen" id="select" title="Paragraph Format" onChange="formatC('formatBlock',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                        <option class="heading" selected>Paragraaf 
                        <option value="&lt;H1&gt;">Heading 1 &lt;H1&gt; 
                        <option value="&lt;H2&gt;">Heading 2 &lt;H2&gt; 
                        <option value="&lt;H3&gt;">Heading 3 &lt;H3&gt; 
                        <option value="&lt;H4&gt;">Heading 4 &lt;H4&gt; 
                        <option value="&lt;H5&gt;">Heading 5 &lt;H5&gt; 
                        <option value="&lt;H6&gt;">Heading 6 &lt;H6&gt; 
                        <option value="&lt;PRE&gt;">Formatted &lt;PRE&gt; 
                        <option value="removeFormat">Remove All 
                      </select>
                      <select name="select" class="TBGen" id="select2" title="Font Name" onChange="formatC('fontname',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                        <option class="heading" selected>Lettertype 
                        <option value="Arial">Arial 
                        <option value="Arial Black">Arial Black 
                        <option value="Arial Narrow">Arial Narrow 
                        <option value="Comic Sans MS">Comic Sans MS 
                        <option value="Courier New">Courier New 
                        <option value="System">System 
                        <option value="Times New Roman">Times New Roman 
                        <option value="Verdana">Verdana 
                        <option value="Wingdings">Wingdings 
                      </select>
                      <select name="select" class="TBGen" id="select3" title="Font Size" onChange="formatC('fontsize',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                        <option class="heading" selected>Grootte 
                        <option value="1">1 
                        <option value="2">2 
                        <option value="3">3 
                        <option value="4">4 
                        <option value="5">5 
                        <option value="6">6 
                        <option value="7">7 
                      </select>
                      <select name="select" class="TBGen" id="select4" title="Font Color" onChange="formatC('forecolor',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                        <option class="heading" selected>Kleur 
                        <option value="red">Rood 
                        <option value="blue">Blauw 
                        <option value="green">Groen 
                        <option value="black">Zwart 
                      </select>
                      <select name="select" class="TBGen" id="select5" title="Font Back Color" onChange="formatC('backcolor',this[this.selectedIndex].value);this.selectedIndex=0" language="javascript">
                        <option class="heading" selected>Achtergrondkleur 
                        <option value="red">Rood 
                        <option value="blue">Blauw 
                        <option value="green">Groen 
                        <option value="black">Zwart 
                        <option value="yellow">Geel 
                        <option value="">Wit 
                      </select>
                      <div class="TBSep"> </div>
                      <div id="EditMode" class="TBGen" title="Editing Mode"> 
                        <input type="checkbox" name="switchMode" LANGUAGE="javascript" onClick="setMode(switchMode.checked)">
                        <a href="#" onClick="document.composeForm.switchMode.click()">Bekijk 
                        HTML broncode</a> <br>
                        <img src="images_cms2/transparantpixel.gif" width="20" height="1"><a href="Javascript:formatC('formatBlock','removeFormat')">Wis 
                        opmaak van selectie </a> </div>
                    </div>
                    <table>
                      <tr> 
                        <td> <div class="Btn" title="Cut" language="javascript" onClick="formatC('cut')"><img class="Ico" src="images_cms2/cut.gif"></div></td>
                        <td> <div class="Btn" title="Copy" language="javascript" onClick="formatC('copy')"><img class="Ico" src="images_cms2/copy.gif"></div></td>
                        <td> <div class="Btn" title="Paste" language="javascript" onClick="formatC('paste')"><img class="Ico" src="images_cms2/paste.gif"></div></td>
                        <td> <div class="Btn" title="Bold" language="javascript" onClick="formatC('bold');"><img class="Ico" src="images_cms2/bold.gif"></div></td>
                        <td> <div class="Btn" title="Italic" language="javascript" onClick="formatC('italic')"><img class="Ico" src="images_cms2/italic.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Underline" language="javascript" onClick="formatC('underline')"><img class="Ico" src="images_cms2/under.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Align Left" name="Justify" language="javascript" onClick="formatC('justifyleft')"><img class="Ico" src="images_cms2/aleft.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Center" name="Justify" language="javascript" onClick="formatC('justifycenter')"><img class="Ico" src="images_cms2/center.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Align Right" name="Justify" language="javascript" onClick="formatC('justifyright')"><img class="Ico" src="images_cms2/aright.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Numbered List" language="javascript" onClick="formatC('insertorderedlist')"><img class="Ico" src="images_cms2/nlist.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Bulletted List" language="javascript" onClick="formatC('insertunorderedlist')"><img class="Ico" src="images_cms2/blist.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Decrease Indent" language="javascript" onClick="formatC('outdent')"><img class="Ico" src="images_cms2/ileft.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Increase Indent" language="javascript" onClick="formatC('indent')"><img class="Ico" src="images_cms2/iright.gif"> 
                          </div></td>
                        <td> <div class="Btn" title="Create Hyperlink" language="javascript" onClick="createLink()"><img class="Ico" src="images_cms2/wlink.gif"> 
                          </div></td>
                      </tr>
                    </table>
                    <iframe class="Composition" width="100%" id="Composition" height="190"></iframe> 
                    <script><!--
							Composition.document.open();
							Composition.document.write(bodyTag);
							Composition.document.close();
							Composition.document.designMode="On";
							// -->
							</script> <input type=hidden name=body>
                    <br>
					<?php echo showTip("volgende regel ? &lt;SHIFT&gt; &lt;ENTER&gt;") ?>
                    </td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
    </tr>
    <tr bgcolor="#EAEAEA"> 
      <td> <input type="button" name="btnOk" value="ok, tekst gebruiken" onClick="opener.returnHTML()"> 
        <input type="button" name="btnAnnuleren" value="annuleren" onClick="self.close();"></td>
    </tr>
  </table>
</form></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<div id="hiddenCompose" style="position: absolute; left: 3; top: -100; visibility: visible; z-index: 3">	
  <form name="hiddencomposeForm">
    <textarea name="hiddencomposeFormTextArea"><?php echo $text ?></textarea>
  </form>
</div>
<p> 
  <script language="javascript">
	bIs_html = true;

	function enviar() {
		error_msg = new Array();
		frm = document.composeForm;

		errors = error_msg.length;

		if(bIs_html) frm.body.value = GetHtml();
		frm.tipo.value = 'send';
		frm.submit();
		
	}
	
	</script>
</p>
<p><br>
</p>

<?php include_once("closepopuppage.php"); ?>
