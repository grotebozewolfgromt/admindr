<?php include_once("openpopuppage.php"); ?>
<?
	if (is_file($local_sitemanagerimages.$file))
	{
		$imgSrc = ImageCreateFromJpeg($local_sitemanagerimages.$file);
		$iWidth = ImageSX($imgSrc); 
		$iHeight = ImageSY($imgSrc); 
		ImageDestroy($imgSrc);
	}
	
?>
<script language="JavaScript">
	parent.textbox.value = '<?php echo $file ?>';
	parent.image.src = '<?php echo $www_sitemanagerimages.$file ?>';
</script>
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<TR>
		<TD width="100%" height="100%" valign="middle" align="center">
			<?
				if (is_file($local_sitemanagerimages.$file))
				{
			?>
			<IMG src="<?php echo $www_sitemanagerimages.$file ?>" class="imgborder">
			<?
				}
				else
				{
					echo "[geen afbeelding]";
				}
			?>
		</TD>
	</TR>
	<TR>
		<TD bgcolor="#F0F0F0">
			<b>naam : </b><?php echo $file; ?><BR>
			<b>breedte : </b><?php echo $iWidth; ?><BR>
			<b>hoogte : </b><?php echo $iHeight; ?>
		</TD>
	</TR>	
</TABLE>
<?php include_once("closepopuppage.php"); ?>
