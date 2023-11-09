<?php

  
	include_once("bootstrap_cms_auth.php");
      

	if (isset($sProgrammerNote))    
            echo $sProgrammerNote 
?>
<html>
<head>
<title><?php echo $sTitelSitemanager ?><?php if (isset($titelsub)) {echo " - $titelsub";} ?></title>
    	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<script language="javascript">
	function popUp(URL)
	{
		day = new Date();
		id = day.getTime();
		eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=400,left = 200,top = 150');");
    } 
	
	function LogOut()
	{
		var bLogOut
		bLogOut = window.confirm("Weet U zeker dat u wilt uitloggen?");
		if (bLogOut)
		{
			location.href = "<?php echo $www_sitemanageradmin ?>index.php";
		}
	}
	
	function confirmDeleteRecord(sUrl, sItemNaam)
	{
		var bConfirm
		bConfirm = window.confirm("Weet u zeker dat u dit item (" + sItemNaam + ") wilt verwijderen ?");
		if (bConfirm)
		{
			location.href = sUrl;
		}
	}
	
	function openHTMLEditor(objTextboxSender) 
	{
		editorwindow=open('<?php echo $www_sitemanageradmin ?>htmleditor-index.php','myname','resizable=yes,statusbar=yes, width=600,height=400');
		//if ((editorwindow.textbox == null) && (editorwindow.textbox != objTextboxSender))
		editorwindow.textbox = objTextboxSender;
	}
	
	function openPicEditor(objTextboxSender, objImageSender) 
	{
		editorwindow=open('<?php echo $www_sitemanageradmin ?>editpic-index.php','myname','resizable=yes,statusbar=yes, width=600,height=400');
		//if ((editorwindow.textbox == null) && (editorwindow.textbox != objTextboxSender))
		editorwindow.textbox = objTextboxSender;
		editorwindow.image = objImageSender;
	}	
</script>
<link rel="stylesheet" href="<?php echo $www_sitemanageradmin ?>stylesheet.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF">