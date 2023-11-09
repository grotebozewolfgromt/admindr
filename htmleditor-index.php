<html>

<head>
<title>HTML editor</title>
</head>
<frameset rows="57,*" cols="*" border="0" style="background-color:buttonface;border:0px;margin:2px">
  <frame name="toolbar" scrolling="no" noresize src="htmleditor-toolbar.php">
  <frame name="content" src="htmleditor-document.php?text=<?php echo $text ?>" scrolling="yes">
  <noframes>
  <body>
  <p>Op deze pagina worden frames gebruikt, maar uw browser ondersteunt geen frames.</p>
  </body>
  </noframes>
</frameset>

</html>
