<?php include_once("openpopuppage.php"); ?>
<script>
self.resizeTo(300,200);
</script>
<?
	if ($resize==1)
	{
		if (is_file($local_sitemanagerimages.$file))
		{
			resizeJPG($local_sitemanagerimages.$file, $local_sitemanagerimages.$file, $edtNewQuality, false, $edtNewWidth, $edtNewHeight);
		?>
			<script>
			window.close();
			</script>		
		<?
		}
	}



	if (is_file($local_sitemanagerimages.$file))
	{
		$imgSrc = ImageCreateFromJpeg($local_sitemanagerimages.$file);
		$iWidth = ImageSX($imgSrc); 
		$iHeight = ImageSY($imgSrc); 
		ImageDestroy($imgSrc);
	}
	
	
?>
<FORM action="editpic-resize.php">
<TABLE border="0" cellpadding="0" cellspacing="0">
	<TR>
		<TD>
			bestand 
		</TD>
		<TD>
			<?php echo $file ?>
		</TD>
	</TR>

	<TR>
		<TD>
			breedte
		</TD>
		<TD>
			<INPUT type="text" name="edtNewWidth" width="10" value="<?php echo $iWidth ?>">
		</TD>
	</TR>
	<TR>
		<TD>
			hoogte
		</TD>
		<TD>
			<INPUT type="text" name="edtNewHeight" width="10" value="<?php echo $iHeight ?>">
		</TD>
	</TR>
	<TR>
		<TD>
			kwaliteit %
		</TD>
		<TD>
			<INPUT type="text" name="edtNewQuality" width="10" value="80">
		</TD>
	</TR>	
	<TR>
		<TD>&nbsp;
			
		</TD>
		<TD>
			<INPUT type="hidden" name="file" value="<?php echo $file ?>">
			<INPUT type="hidden" name="resize" value="1">
			<INPUT type="submit" value="resize">
		</TD>
	</TR>
	<TR>
		<TD>&nbsp;
			
		</TD>
		<TD>
			<IMG src="<?php echo $www_sitemanagerimages.$file ?>">
		</TD>
	</TR>		
</TABLE>
</FORM>
<?php include_once("closepopuppage.php"); ?>
