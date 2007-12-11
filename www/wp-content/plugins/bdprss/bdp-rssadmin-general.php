<?php
//	if ($user_level < 6) die ( __('Cheatin&#8217; uh?') );  	// safety net to ctach cheets
?>

<h2>Manage the BDP RSS Aggregator plugin</h2>

<h3>Using the <a href="http://www.ozpolitics.info/blog/?p=87">BDP RSS Aggregator</a> is as easy as 1-2-3-4:</h3>

<ol>
<li><b><a href="#addfeeds">add the feeds</a></b> you wish to provide on your site - multiple feeds can be entered as a comma separated list</li>
<li><b><a href="#createformat">create/update the output format</a></b> that will be used to output the feeds collected</li>
<li><b><a href="#setpolling">set/change the feed capture attributes</a></b> that will be used to poll the listed feeds</li>
<li><b>place a call to the BDPRSS2::output(n) function</b> (for example) in your side bar, where the argument 'n' is the output format identifier</li>
</ol>


<p><hr /></p>

<fieldset>
<a name="addfeeds"></a>
<h3>Step 1: Feeds</h3>
<h4>Add a new feed</h4>
<form method="post" action="<?php echo $selfreference; ?>">
	<table border="0" width="100%" cellspacing="2" cellpadding="5" class="editform"> 
		<tr valign="middle">
			<td align="left"><strong>RSS Address:</strong>
				<input type="text" name="bdprss_new_feed_name" value="" size="60" /></td>
			<td align="right">
				<input type="submit" name="bdprss_add_feed_button" value="Add Feed &raquo;" />
			</td>
		</tr>
	</table>
</form>

<?php 
global $bdprss_db;
$overriden = FALSE;
if($bdprss_db->count_in_sitetable()) {
?>
	<hr width='50%' />
	<h4>Current feeds</h4>
	<table width="100%" cellpadding="3" cellspacing="3">
<?php 
	$file = get_settings('siteurl') . '/wp-admin/edit.php?page=' . BDPRSS2_DIRECTORY. '/bdp-rssadmin.php';
	$now = $bdprss_db->now;

	echo "<tr><th align='left'>Site and feed status</th><th>Site updated</th>".
		"<th>GMT adj</th><th>Polling freq</th>".
		"<th>Last poll</th><th>Cache updated</th><th>Next poll</th>".
		"<th colspan='3'>Actions</th></tr>\n";
	
	$result = $bdprss_db->get_all_sites();
	
	$benchmark = $now - (60 * 60 * 24 * 7);	// highlight blogs with updates older than 7 days
	
	if($result) {
		foreach($result as $r) {
			$ref = $r->{$bdprss_db->cidentifier};
			$url = $r->{$bdprss_db->cfeedurl};
			$site = $r->{$bdprss_db->csitename};
			$siteurl = $r->{$bdprss_db->csiteurl};
			$description = $r->{$bdprss_db->cdescription};
			$gmt = $r->{$bdprss_db->cgmtadjust};
			$polled = BDPRSS2::getage( $r->{$bdprss_db->clastpolltime} );
			$updated = BDPRSS2::getage( $r->{$bdprss_db->cupdatetime} );
			$scheduled = BDPRSS2::getage( $r->{$bdprss_db->cnextpolltime} );
			$pollingfreq = $r->{$bdprss_db->cpollingfreqmins};
			if($scheduled == 'never') $scheduled = 'now';
			$sno = $r->{$bdprss_db->csitenameoverride} == 'Y';
			
			$siteUpdate = '';
			$name = $url;
			if($site) 
			{
				$italics = ''; $unitalics = '';
				if($sno) { $italics = '<em>'; $unitalics = '</em>'; $overriden = TRUE; }
				$name = "$italics<a href='$siteurl' title='$description'>"."$site</a>$unitalics";
				$name .= " [<a href='$url' title='$url'>feed</a>]";
				
				$ticks = $bdprss_db->get_most_recent_item_time($ref);
				$bold = ''; $unbold ='';
				if($ticks < $benchmark) { $bold = '<strong>'; $unbold ='</strong>'; }
				$siteUpdate = " $bold".BDPRSS2::getage($ticks)."$unbold"; 
			}
			
			$bold = '';
			$unbold = '';
			if( $r->{$bdprss_db->clastpolltime} != $r->{$bdprss_db->cupdatetime} ) { 
				$bold = '<strong>'; 
				$unbold = '</strong>'; 
			}
			
			$sbold = ''; $sunbold = '';
			if($r->{$bdprss_db->cnextpolltime} < $now)
			{
				$sbold = '<strong>'; 
				$sunbold = '</strong>'; 
			}
			
			$class = ('alternate' == $class) ? '' : 'alternate';
			
			$errorCount = $bdprss_db->countErrors($url);
			if($errorCount) 
				$name .= " <a href='".$file.
					"&amp;action=errorlist&amp;rss=$ref'>[<strong>E&nbsp;$errorCount</strong>]</a>";
			
			echo "<tr class='$class' valign='middle' align='center'>\n".
				"\t<td align='left'>$name</td>\n".
				"\t<td align='center'>$siteUpdate</td>\n".
				"\t<td align='center'>";
			if($gmt != 0.0)	echo $gmt;
			echo "</td>\n".
				"\t<td align='center'>";
			if(	$pollingfreq ) echo "$pollingfreq minutes";
			echo "</td>\n" .
				"\t<td align='center'>$polled</td>\n" .
				"\t<td align='center'>$bold $updated $unbold</td>\n".
				"\t<td align='center'>$sbold $scheduled $sunbold</td>\n".
				"\t<td align='center'><a href='" .$file. 
				"&amp;action=editfeed&amp;rss=$ref'>Edit</a></td>\n".
				"\t<td align='center'><a href='" .$file. 
				"&amp;action=update&amp;rss=$ref'>Poll</a></td>\n".
				"\t<td align='center'><a href='" .$file. 
				"&amp;action=delete&amp;rss=$ref'>Delete</a></td>\n".
				"</tr>\n";
		}
	}
}
?>
</table>

<?php

if($overriden) 
	echo "<p><b>Note:</b> Sites in italics have had their feed details overridden. ".
	"To restore feed details: first edit the site, second un-tick the box to switch off the feed override, ".
	"then hit the edit button, and finally poll the feed. </p>\n";

// improved monitoring of feed errors
if($bdprss_db->countErrors())
{
	echo "<hr width='50%' />\n\n<h4>Recent feed errrors</h4>\n\n";

	echo "<table width='100%' cellpadding='3' cellspacing='3'>\n";
	echo "<tr><td align='left'>".
		"<a href='".$file."&amp;action=errorlist'>List all feed errors</a></td>\n";
	echo "<td align='right'>".
		"<a href='".$file."&amp;action=errordelete'>Delete the feed error table</a></td>\n";
	echo "</tr></table>";
}

// lets put the list of URLs in the OUTPUT - they can be useful!
if($result) 
{
	echo "<hr width='50%' />\n\n<h4>A list of feeds</h4>\n\n";

	echo "<p>This list can be copied and saved to a file. If you delete everything, ".
		"you can paste the list into the add feeds box.</p>\n<p>";
	$subsequent = false;
	foreach($result as $r) 
	{
		if($subsequent) echo " ";
		$subsequent = true;
		$url = $r->{$bdprss_db->cfeedurl};
		echo $url;
	}
	echo "</p>\n";
}
?>
</fieldset>



<p><hr /></p>



<fieldset>
<a name="createformat"></a>
<h3>Step 2: Output formats</h3>

<h4><a href="<?php echo "$selfreference&amp;action=createlist"; ?>">Create a new output format</a></h4>

<?php 
global $bdprss_db;
if($bdprss_db->count_in_listtable()) {
?>

	<h4>Current formats</h4>
	<table width="100%" cellpadding="3" cellspacing="3">
<?php 
	global $bdprss_db;

	$file = get_settings('siteurl') . '/wp-admin/edit.php?page=' . BDPRSS2_DIRECTORY. '/bdp-rssadmin.php';

	echo "<tr><th>Output format ID</th><th width='75%'>Name</th><th colspan='2'>Actions</th></tr>\n";

	$result = $bdprss_db->get_all_lists();

	if($result) {
		$class = '';
		foreach($result as $r) {
			$id = $r->{$bdprss_db->lidentifier};
			$name = htmlspecialchars($r->{$bdprss_db->lname});

			$class = ('alternate' == $class) ? '' : 'alternate';

			echo "<tr class='$class' valign='middle' align='center'>\n".
				"<td>$id</td><td width='75%'>$name</td>".
				"<td><a href='$selfreference&amp;action=editlist&amp;list=$id'>Edit</a></td>".
				"<td><a href='$selfreference&amp;action=dellist&amp;list=$id'>Delete</a></td>".
				"</tr>";
		}
	}
}

?>
</table>
</fieldset>



<p><hr /></p>



<fieldset>
<a name="setpolling"></a>
<h3>Step 3: Set the feed capture attributes</h3>
<form method="post" action="<?php echo $selfreference; ?>">
	
	<table border="0" width="100%" cellspacing="2" cellpadding="5" class="editform"> 
	<tr valign="top">
		<th  width="40%" scope="row">
			Standard feed polling frequency:
		</th>
		<td>
			<input type="range" name="bdprss_new_frequency" 
					value="<?php echo get_option('bdprss_update_frequency'); ?>" 
					maxlength="3" size="3" /> minutes<br>
					This frequency is used for all sites unless a non-standard
					frequency is set above.
		</td>
	</tr>
	<tr valign="top">
		<th  width="40%" scope="row">
			Keep feed items for:
		</th>
		<td>
			<input type="range" name="bdprss_keep_howlong" 
					value="<?php echo intval(get_option('bdprss_keep_howlong')); ?>" 
					maxlength="3" size="3" /> months (zero = forever)
		</td>
	</tr>
	<tr valign="top">
		<td align="right" colspan="2">
			<input type="submit" name="bdprss_change_frequency_button" 
			value="Change &raquo;" />
		</td>
	</tr>
	</table>

</form>
</fieldset>



<p><hr /></p>



<h3>Other miscellenous features</h3>

<fieldset>
<h4>Poll all RSS feeds</h4>
<p>Be patient, this can take some time.</p>
<form method="post" action="<?php echo $selfreference; ?>">
		<p align="right">
			<input type="submit" name="bdprss_poll_all_button" value="Poll everything" />
		</p>
</form>
</fieldset>


<fieldset>
<h4>The &quot;delete everything&quot; button of death!</h4>
<p>This button recreates the tables in your database, which might be necessary if you are upgrading from an earlier version of the RSS Aggregator.</p>
<p>There are <b><u>no second chances</u></b>. If you click on the button of death everything will be 
deleted.</p>
<form method="post" action="<?php echo $selfreference; ?>">
		<p align="right">
			<input type="submit" name="bdprss_button_of_death" value="Delete everything!" />
		</p>
</form>
</fieldset>




<br />




<p><hr /></p>

<fieldset>
<h3>Notes:</h3> 
<ul>

<li>Although <b>designed for <a href="http://wordpress.org/">Wordpress'</a> RSS 2.0 feeds</b>, 
this system should work with the RDF feeds generated by <a href="http://www.sixapart.com/">Typepad</a> 
and the ATOM feeds generated by <a href="http://www.blogger.com/start">Blogger</a>.
It will not work with synthetic or multi-channel RSS 1.0 feeds. But this should not be too much 
of a problem as RSS 2.0 is a single channel feed standard.</li>

<li>The number of feeds you will be able to carry will depend on the volume of traffic to your site,
and the polling frequency. Typically each hit to your site will update one feed. The more visitors per hour, 
and the wider the polling interval, the more feeds you will be able to carry. </li>

</ul>
</fieldset>

