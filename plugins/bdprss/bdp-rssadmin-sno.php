<?php 

//if ($user_level < 6) die ( __('Cheatin&#8217; uh?') ); 

$result = $bdprss_db->get_site_by_id($rss);
if($result) $cidentifier = $result->{$bdprss_db->cidentifier};

?>

<h2>Edit site feed information:</h2>


<fieldset>

<form method="post" action="<?php echo $selfreference; ?>">


<?php 

if(!$result || ($rss != $cidentifier) ) {
	echo "<p><b>Unexpected error:</b> could not find feed $rss, or it differed from $cidentifier</p>\n";
} else {
	$cfeedurl = 			bdpDisplayCode($result->{$bdprss_db->cfeedurl});
	$csitename =			bdpDisplayCode($result->{$bdprss_db->csitename});
	$cdescription = 		bdpDisplayCode($result->{$bdprss_db->cdescription});
	$csiteurl =				bdpDisplayCode($result->{$bdprss_db->csiteurl});
	$cgmtadjust = 			$result->{$bdprss_db->cgmtadjust};
	$csitenameoverride = 	$result->{$bdprss_db->csitenameoverride};
	$cpollingfreqmins =		$result->{$bdprss_db->cpollingfreqmins};
?>


	<table width="100%" cellspacing="2" cellpadding="5" class="editform">

	<tr valign="top">
		<th  width="40%" scope="row">
			Feed URL:
		</th>
		<td>
			<?php echo "$cfeedurl [id:$cidentifier]"; ?>
			<input type="hidden" name="bdprss_cidentifier" value="<?php echo $cidentifier; ?>" />
		</td>
	</tr>

	<tr valign="top">
		<th  width="40%" scope="row">
			Feed name:
		</th>
		<td>
			<input type="text" name="bdprss_csitename" value="<?php echo $csitename; ?>" size="50" />
		</td>
	</tr>

	<tr valign="top">
		<th  width="40%" scope="row">
			Feed description:
		</th>
		<td>
			<textarea cols="50" rows="4" name="bdprss_cdescription"><?php echo $cdescription; ?></textarea>
		</td>
	</tr>

	<tr valign="top">
		<th  width="40%" scope="row">
			Site URL:
		</th>
		<td>
			<input type="text" name="bdprss_csiteurl" value="<?php echo $csiteurl; ?>" size="50" />
		</td>
	</tr>

	<tr valign="top">
		<th  width="40%" scope="row">
			Override site information in feed:
		</th>
		<td>
			<input type="checkbox" name="bdprss_csitenameoverride" 
				value="Y" <?php if($csitenameoverride == 'Y') echo ' checked="checked"'; ?>>
				This must be set to save the above information.
		</td>
	</tr>

	<tr valign="top">
		<th  width="40%" scope="row">
			GMT adjustment:
		</th>
		<td>
			<input type="text" name="bdprss_cgmtadjust" value="<?php echo $cgmtadjust; ?>" size="4" />
			hours<br>
			Use range: -48.0 &lt;= adjustment &lt;= 48.0<br>
			Note: this field will be saved even if the override is not set.<br>
		</td>
	</tr>

	<tr valign="top">
		<th  width="40%" scope="row">
			Set a non-standard site polling frequency:
		</th>
		<td>
			<input type="text" name="bdprss_cpollingfreqmins" 
				value="<?php echo $cpollingfreqmins; ?>" size="4" /> minutes<br>
			Use range: 0 &lt;= frequency &lt;= 9999<br>
			Zero means use the standard site polling frequency.<br>
			Note: 9999 minutes = 6.94 days, which approximates a weekly poll.<br>
			Note: for a daily poll use 1440 minutes.<br>
			Note: this field will be saved even if the override is not set.
		</td>
	</tr>
	<tr valign="top">
		<td colspan="2" align="right">
			<input type="submit" name="bdprss_edit_site_button" value="Edit &raquo;" />
		</td>
	</tr>

	</table>

<?php
}
?>
</form>
</fieldset>
