<?php 

//if ($user_level < 6) die ( __('Cheatin&#8217; uh?') ); 

?>

<h2>Edit RSS feed output format #<?php echo $list; ?></h2>

<fieldset>

<form method="post" action="<?php echo $selfreference; ?>">


<h3>Edit output format </h3>

<?php 

global $bdprss_db;

$result = $bdprss_db->get_list($list);
if(!$result) {
	echo "<p><b>Unexpected error:</b> could not find output format #$list </p>\n";
} else {
	// setup stuff
	$lname = bdpDisplayCode($result->{$bdprss_db->lname});
	$ltype = $result->{$bdprss_db->ltype};
	$lage = $result->{$bdprss_db->lage};
	$lshort = $result->{$bdprss_db->lshort};
	$litemspersite = $result->{$bdprss_db->litemspersite};
	$lmaxlength = $result->{$bdprss_db->lmaxlength};
	$llistall = $result->{$bdprss_db->llistall};
	$llaunchnew = $result->{$bdprss_db->llaunchnew};

	$lurls = $result->{$bdprss_db->lurls};
	$ids = preg_split("','", $lurls, -1, PREG_SPLIT_NO_EMPTY);
	//for($i = 0; $i < sizeof($ids); $i++) echo "DEBUG '$ids[$i]'\n";		/* DEBUG */

	$lprefeed = bdpDisplayCode($result->{$bdprss_db->lprefeed});
	$lpostfeed = bdpDisplayCode($result->{$bdprss_db->lpostfeed});

	$lpresite = bdpDisplayCode($result->{$bdprss_db->lpresite});
	$lpostsite = bdpDisplayCode($result->{$bdprss_db->lpostsite});

	$lpresitename = bdpDisplayCode($result->{$bdprss_db->lpresitename});
	$lpostsitename = bdpDisplayCode($result->{$bdprss_db->lpostsitename});

	$lpreitemset = bdpDisplayCode($result->{$bdprss_db->lpreitemset});
	$lpostitemset = bdpDisplayCode($result->{$bdprss_db->lpostitemset});

	$lpreitem = bdpDisplayCode($result->{$bdprss_db->lpreitem});
	$lpostitem = bdpDisplayCode($result->{$bdprss_db->lpostitem});

	$lpreitemname = bdpDisplayCode($result->{$bdprss_db->lpreitemname});
	$lpostitemname = bdpDisplayCode($result->{$bdprss_db->lpostitemname});

	$lpreitemage = bdpDisplayCode($result->{$bdprss_db->lpreitemage});
	$lpostitemage = bdpDisplayCode($result->{$bdprss_db->lpostitemage});

	$lpreitembody = bdpDisplayCode($result->{$bdprss_db->lpreitembody});
	$lpostitembody = bdpDisplayCode($result->{$bdprss_db->lpostitembody});

	$lbetwixtsiteitem = bdpDisplayCode($result->{$bdprss_db->lbetwixtsiteitem});

	$lmaxwordlength = $result->{$bdprss_db->lmaxwordlength};

	$lallowablexhtmltags = preg_split("','", $result->{$bdprss_db->lallowablexhtmltags},
		-1, PREG_SPLIT_NO_EMPTY);
		
	$loutputsitename = bdpDisplayCode($result->{$bdprss_db->loutputsitename});

	$larchiveviewpage = bdpDisplayCode($result->{$bdprss_db->larchiveviewpage});
	$lprearchivelist = bdpDisplayCode($result->{$bdprss_db->lprearchivelist});
	$lpostarchivelist = bdpDisplayCode($result->{$bdprss_db->lpostarchivelist});
	$lprearchivedate = bdpDisplayCode($result->{$bdprss_db->lprearchivedate});
	$lpostarchivedate = bdpDisplayCode($result->{$bdprss_db->lpostarchivedate});

	$liscachable = bdpDisplayCode($result->{$bdprss_db->liscachable});
	$lcacheviewpage = bdpDisplayCode($result->{$bdprss_db->lcacheviewpage});
	$lprecacheitem = bdpDisplayCode($result->{$bdprss_db->lprecacheitem});
	$lpostcacheitem = bdpDisplayCode($result->{$bdprss_db->lpostcacheitem});
	$lprecachetitle = bdpDisplayCode($result->{$bdprss_db->lprecachetitle});
	$lpostcachetitle = bdpDisplayCode($result->{$bdprss_db->lpostcachetitle});
	$lprecachesite = bdpDisplayCode($result->{$bdprss_db->lprecachesite});
	$lpostcachesite = bdpDisplayCode($result->{$bdprss_db->lpostcachesite});
	$lprecacheitemtime = bdpDisplayCode($result->{$bdprss_db->lprecacheitemtime});
	$lpostcacheitemtime = bdpDisplayCode($result->{$bdprss_db->lpostcacheitemtime});
	$lprecacheupdatetime = bdpDisplayCode($result->{$bdprss_db->lprecacheupdatetime});
	$lpostcacheupdatetime = bdpDisplayCode($result->{$bdprss_db->lpostcacheupdatetime});
	
	$lcanrss = bdpDisplayCode($result->{$bdprss_db->lcanrss});
	$lrssfullorsummary = bdpDisplayCode($result->{$bdprss_db->lrssfullorsummary});
	$lrssnumbersingle = bdpDisplayCode($result->{$bdprss_db->lrssnumbersingle});
	$lrsslink = bdpDisplayCode($result->{$bdprss_db->lrsslink});
	$lrssdescription = bdpDisplayCode($result->{$bdprss_db->lrssdescription});
?>

<table width="100%" cellspacing="2" cellpadding="5" class="editform">
<tr valign="top"><td align="left" colspan="2"><h4>About this list</h4></td></tr>
<tr valign="top">
	<th  width="40%" scope="row">
		Output format identifier:
	</th>
	<td>
		<?php echo $list; ?>
		<input type="hidden" name="bdprss_lidentifier" value="<?php echo $list; ?>" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Output format name:
	</th>
	<td>
		<input type="text" name="bdprss_lname" value="<?php echo $lname; ?>" size="50" /><br />
		(Only used for list management)
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Output format type:
	</th>
	<td>

		<input type="radio" name="bdprss_ltype" 
			value="countrecentitem"<?php if($ltype == 'countrecentitem') echo ' checked="checked"'; ?>>
			List by most recent items<br />
		<input type="radio" name="bdprss_ltype" 
			value="daterecentitem"<?php if($ltype == 'daterecentitem') echo ' checked="checked"'; ?>>
			List by most recent items for today<br />
		<input type="radio" name="bdprss_ltype" 
			value="sitealpha"<?php if($ltype == 'sitealpha') echo ' checked="checked"'; ?>>
			List by sites alphabetically<br />
		<input type="radio" name="bdprss_ltype" 
			value="siteupdate"<?php if($ltype == 'siteupdate') echo ' checked="checked"'; ?>>
			List by sites most recently polled
	</td>
</tr>

<tr valign="top"><td align="left" colspan="2"><h4>About the sites</h4></td></tr>

<tr valign="top">
	<th  width="40%" scope="row">
		List all sites:
	</th>
	<td>
		<input type="checkbox" name="bdprss_llistall" 
			value="Y" <?php if($llistall == 'Y') echo ' checked="checked"'; ?>>
			(If not checked, the following checked sites will be listed)
	</td>
</tr>

<tr><td colspan="2">
<table width="100%" cellpadding="3" cellspacing="3">
<?php

	global $bdprss_db;
	$result = $bdprss_db->get_all_sites();

	if($result) {
		echo "<tr align='center'><td><b>Check</b></td><td><b>ID</b></td>".
			"<td align='left'><b>Feed</b></td><td><b>Cache Last Updated</b></td></tr>\n";
		$class = '';

		foreach($result as $r) {
			$id = $r->{$bdprss_db->cidentifier};
			$url = $r->{$bdprss_db->cfeedurl};
			$site = $r->{$bdprss_db->csitename};
			
			$updated = BDPRSS2::getage( $r->{$bdprss_db->cupdatetime} );
			$name = ($site == '') ? $url : "<a href='$url' title='$url'>"."$site</a>";
			
			$class = ('alternate' == $class) ? '' : 'alternate';
			
			echo "<tr class='$class' valign='middle' align='center'>\n";
			echo "\t<td><input type='checkbox' name='bdprss_feed_$id' value='1'";
			if(array_search($id, $ids) !== false) echo " checked='checked'";
			echo "></td>\n";

			echo "\t<td>$id</td>\n";
			echo "\t<td align='left'>$name</td>\n";
			echo "\t<td>$updated</td>\n";
			echo "</tr>\n";
		}
	}
?>
</table>
</td></tr>

<tr valign="top"><td align="left" colspan="2"><h4>About the items</h4></td></tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Maximum items per site or<br />Maximum recent hits:
	</th>
	<td>
		<input type="text" name="bdprss_litemspersite" 
			value="<?php echo $litemspersite; ?>" size="5" />
			(Zero means no limit)
			
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Print site names:
	</th>
	<td>
		<input type="checkbox" name="bdprss_loutputsitename" 
			value="Y" <?php if($loutputsitename == 'Y') echo ' checked="checked"'; ?>>
			
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Only print an item's title:
	</th>
	<td>
		<input type="checkbox" name="bdprss_lshort" 
			value="Y" <?php if($lshort == 'Y') echo ' checked="checked"'; ?>>
			(ie. don't print a summary for each item)
			
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Print the age of a post after its title:
	</th>
	<td>
		<input type="checkbox" name="bdprss_lage" 
			value="Y" <?php if($lage == 'Y') echo ' checked="checked"'; ?>>
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Maximum words for each item:
	</th>
	<td>
		<input type="text" name="bdprss_lmaxlength" value="<?php echo $lmaxlength; ?>" size="5" /><br />
		(zero = no maximum word limit)
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Maximum length of words in each item:
	</th>
	<td>
		<input type="text" name="bdprss_lmaxwordlength" 
			value="<?php echo $lmaxwordlength; ?>" size="5" /><br />
		(zero = no maximum word limit)
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Launch links in a new window:
	</th>
	<td>
		<input type="checkbox" name="bdprss_llaunchnew" 
			value="Y" <?php if($llaunchnew == 'Y') echo ' checked="checked"'; ?>>
	</td>
</tr>





<tr valign="top"><td align="left" colspan="2"><h4>XHTML formatting for list presentation</h4></td></tr>


<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the entire list *:
	</th>
	<td>
		<input type="text" name="bdprss_lprefeed" 
			value="<?php echo $lprefeed; ?>" size="30" />
		{LIST OF ALL FEEDS}
		<input type="text" name="bdprss_lpostfeed" 
			value="<?php echo $lpostfeed; ?>" size="10" />
		
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after each site in feed list *:
	</th>
	<td>
		<input type="text" name="bdprss_lpresite" 
			value="<?php echo $lpresite; ?>" size="30" />
		{EACH SITE}
		<input type="text" name="bdprss_lpostsite" 
			value="<?php echo $lpostsite; ?>" size="10" />
		
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the title for each site *:
	</th>
	<td>
		<input type="text" name="bdprss_lpresitename" 
			value="<?php echo $lpresitename; ?>" size="30" />
		{SITE TITLE}
		<input type="text" name="bdprss_lpostsitename" 
			value="<?php echo $lpostsitename; ?>" size="10" />
		
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the site item list:
	</th>
	<td>
		<input type="text" name="bdprss_lpreitemset" 
			value="<?php echo $lpreitemset; ?>" size="30" />
		{LIST OF ITEMS}
		<input type="text" name="bdprss_lpostitemset" 
			value="<?php echo $lpostitemset; ?>" size="10" />
		
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after each item in list:
	</th>
	<td>
		<input type="text" name="bdprss_lpreitem" 
			value="<?php echo $lpreitem; ?>" size="30" />
		{EACH ITEM}
		<input type="text" name="bdprss_lpostitem" 
			value="<?php echo $lpostitem; ?>" size="10" />
		
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after each item's title:
	</th>
	<td>
		<input type="text" name="bdprss_lpreitemname" 
			value="<?php echo $lpreitemname; ?>" size="30" />
		{ITEM TITLE}
		<input type="text" name="bdprss_lpostitemname" 
			value="<?php echo $lpostitemname; ?>" size="10" /><br />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after each item's age:
	</th>
	<td>
		<input type="text" name="bdprss_lpreitemage" 
			value="<?php echo $lpreitemage; ?>" size="30" />
		{ITEM TITLE}
		<input type="text" name="bdprss_lpostitemage" 
			value="<?php echo $lpostitemage; ?>" size="10" /><br />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after each item body:
	</th>
	<td>
		<input type="text" name="bdprss_lpreitembody" 
			value="<?php echo $lpreitembody; ?>" size="30" />
		{ITEM BODY}
		<input type="text" name="bdprss_lpostitembody" 
			value="<?php echo $lpostitembody; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Between site name and item name:
	</th>
	<td>
		<input type="text" name="bdprss_lbetwixtsiteitem" 
			value="<?php echo $lbetwixtsiteitem; ?>" size="30" /><br />
		(Only used when listing recent items)
	</td>
</tr>

<tr valign="top">
	<td colspan="2" align="center">
		<b>* Asterisked items not used when listing recent items</b>
	</td>
</tr>

<tr valign="top"><td align="left" colspan="2"><h4>XHTML tags to retain in list output</h4></td></tr>

<?php

	foreach($bdprssTagSet as $key => $value)
	{
		$tag = BDPRSS2::tagalise($key);
		echo '<tr valign="top"><th  width="40%" scope="row">';
		echo $key;
		$checked = array_search($key, $lallowablexhtmltags);
		if($checked !== FALSE) $checked = "checked='checked' "; else $checked='';
		echo "</th><td><input type='checkbox' name='$tag' $checked /> [";
			foreach($value as $v) echo " $v";
		echo "&nbsp;] </td></tr>\n";
	}
?>






<tr valign="top">
	<td align="left" colspan="2">
		<h4>Archive listing</h4>
		<p>These features affect the BDPRSS2::archiveList() function</p>
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the list of archive dates:
	</th>
	<td>
		<input type="text" name="bdprss_lprearchivelist" 
			value="<?php echo $lprearchivelist; ?>" size="30" />
		{LIST OF ARCHIVE DATES}
		<input type="text" name="bdprss_lpostarchivelist" 
			value="<?php echo $lpostarchivelist; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the individual archive dates:
	</th>
	<td>
		<input type="text" name="bdprss_lprearchivedate" 
			value="<?php echo $lprearchivedate; ?>" size="30" />
		{EACH ARCHIVE DATE}
		<input type="text" name="bdprss_lpostarchivedate" 
			value="<?php echo $lpostarchivedate; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Page with the BDPRSS2::output() function:
	</th>
	<td>
			<input type="text" name="bdprss_larchiveviewpage" 
			value="<?php echo $larchiveviewpage; ?>" size="50" /><br />
			[Optional - if empty assume current page]
	</td>
</tr>





<tr valign="top"><td align="left" colspan="2"><h4>Cache item presentation</h4></td></tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Allow people to view the RSS cache for items:
	</th>
	<td>
		<input type="checkbox" name="bdprss_liscachable" 
			value="Y" <?php if($liscachable == 'Y') echo ' checked="checked"'; ?>>
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		URL for the page with the BDPRSS2::viewCache() function:
	</th>
	<td>
			<input type="text" name="bdprss_lcacheviewpage" 
			value="<?php echo $lcacheviewpage; ?>" size="50" /> [Required]
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the cache item:
	</th>
	<td>
		<input type="text" name="bdprss_lprecacheitem" 
			value="<?php echo $lprecacheitem; ?>" size="30" />
		{RSS CACHE ITEM}
		<input type="text" name="bdprss_lpostcacheitem" 
			value="<?php echo $lpostcacheitem; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the item title:
	</th>
	<td>
		<input type="text" name="bdprss_lprecachetitle" 
			value="<?php echo $lprecachetitle; ?>" size="30" />
		{ITEM TITLE}
		<input type="text" name="bdprss_lpostcachetitle" 
			value="<?php echo $lpostcachetitle; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the site name:
	</th>
	<td>
		<input type="text" name="bdprss_lprecachesite" 
			value="<?php echo $lprecachesite; ?>" size="30" />
		{SITE NAME}
		<input type="text" name="bdprss_lpostcachesite" 
			value="<?php echo $lpostcachesite; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the item post time:
	</th>
	<td>
		<input type="text" name="bdprss_lprecacheitemtime" 
			value="<?php echo $lprecacheitemtime; ?>" size="30" />
		{POST TIME}
		<input type="text" name="bdprss_lpostcacheitemtime" 
			value="<?php echo $lpostcacheitemtime; ?>" size="10" />
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Before and after the RSS capture time:
	</th>
	<td>
		<input type="text" name="bdprss_lprecacheupdatetime" 
			value="<?php echo $lprecacheupdatetime; ?>" size="30" />
		{RSS CAPTURE TIME}
		<input type="text" name="bdprss_lpostcacheupdatetime" 
			value="<?php echo $lpostcacheupdatetime; ?>" size="10" />
	</td>
</tr>





<tr valign="top"><td align="left" colspan="2"><h4>RSS feed from this list</h4></td></tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Allow an RSS feed to be provided for this list:
	</th>
	<td>
		<input type="checkbox" name="bdprss_lcanrss" 
			value="Y" <?php if($lcanrss == 'Y') echo ' checked="checked"'; ?>>
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		URL for RSS page:
	</th>
	<td>
			<input type="text" name="bdprss_lrsslink" 
			value="<?php echo $lrsslink; ?>" size="50" /><br /> 
			[Required if providing an RSS feed]
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Feed description:
	</th>
	<td>
			<input type="text" name="bdprss_lrssdescription" 
			value="<?php echo $lrssdescription; ?>" size="50" /><br /> 
			[Recommended if providing an RSS feed]
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Full text or summary text in RSS feeds:
	</th>
	<td>
		<input type="radio" name="bdprss_lrssfullorsummary" value="FULL"
			<?php if($lrssfullorsummary == 'FULL') echo ' checked="checked"'; ?>>
			Full<br />
		<input type="radio" name="bdprss_lrssfullorsummary" value="SUMMARY"
			<?php if($lrssfullorsummary == 'SUMMARY') echo ' checked="checked"'; ?>>
			Summary
	</td>
</tr>

<tr valign="top">
	<th  width="40%" scope="row">
		Number of posts in an RSS feed:
	</th>
	<td>
		<input type="text" name="bdprss_lrssnumbersingle" 
			value="<?php echo $lrssnumbersingle; ?>" size="5" /> <br />
			[Must be greater than zero if providing an RSS feed]
	</td>
</tr>




<tr valign="top"><td align="left" colspan="2"><h4>And hit the edit button to complete</h4></td></tr>

<tr valign="top">
	<td colspan="2" align="right">
		<input type="submit" name="bdprss_edit_list_button" value="Edit &raquo;" />
	</td>
</tr>


</table>

</form>

</fieldset>

<?php
}
?>
