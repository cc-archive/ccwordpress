<?php 

/* ----- Access validation ----- */

//if ($user_level < 5) die ( __('Cheatin&#8217; uh?') );


/* ----- Admin functions and initialisation ----- */

function bdpSetFeed($sitelist='http://www.ozpolitics.info/blog/?feed=rss2') 
{
	global $bdprss_db;

	$sitelist = trim($sitelist);
	if($sitelist == '') 
		return	'<div class="wrap"><h3>Warning</h3>No RSS site was specified\n</div>';
			
	$sites = preg_split("'[ \n\t\r]+'", $sitelist, -1, PREG_SPLIT_NO_EMPTY); // space separated list
			
	// we start at two so that $bdprss_db->clastpolltime != $bdprss_db->cupdatetime
	$count = 2;	 
			
	// add to the site list
	foreach($sites as $s) 
	{
		$s = trim($s);
				
		if(!$s) continue;
				
		if(!$bdprss_db->is_in_sitetable($s)) 
		{ 
			$result = $bdprss_db->insert_in_sitetable($s, $count);
			$count++;
		} 
	}
	return FALSE;
}

function bdpSetFrequency($freq='') 
{
	if(!$freq || !is_int($freq) || $freq<1 || $freq>999) 
		$freq = 60;	// 60 minutes is the default
	update_option('bdprss_update_frequency', $freq);
}

function bdpDisplayCode($text)
{
//	Stored in DB	Displayed		Note
//	-----------------------------------------------------------
//	>				&gt;			close tag
//  &gt;			&amp;gt;		right angle bracket
//	&quot;			&quot;			double quotation mark --> "
//	&nbsp;			&amp;nbsp;		non breaking space

	$text = mb_ereg_replace('&39;', "'", $text);
	$text = mb_ereg_replace('&quot;', '"', $text);

	$text = mb_ereg_replace('&', '&amp;', $text);

	$text = mb_ereg_replace("'", '&39;', $text);
	$text = mb_ereg_replace('"', '&quot;', $text);
	$text = mb_ereg_replace('<', '&lt;', $text);
	$text = mb_ereg_replace('>', '&gt;', $text);

	return ($text);
}

if(!(int) get_option('bdprss_update_frequency')) bdpSetFrequency();	// initialise polling frequency

global $bdprss_db;

/* ----- Capture and process form variables ----- */


if( isset($_POST['bdprss_add_feed_button']) )
{
	$r = bdpSetFeed($_POST['bdprss_new_feed_name']);
	if($r) echo $r;
}

if( isset($_POST['bdprss_change_frequency_button']) )
{
	bdpSetFrequency((int) $_POST['bdprss_new_frequency']);

	$howlong = (int) $_POST['bdprss_keep_howlong'];
	if($howlong < 0 || $howlong > 99999) $howlong = 0;	// zero default - never delete
	update_option('bdprss_keep_howlong', 		$howlong);
}

if( isset($_POST['bdprss_button_of_death']) )
{
	$bdprss_db->reset();
}

if( isset($_POST['bdprss_poll_all_button']) )
{
	$bdprss_db->updateAll();
}

if( isset($_POST['bdprss_edit_site_button']) )
{
	require_once(dirname(__FILE__) . '/bdp-rssfeed.php');
	
	$siteArray = array();

	$csitenameoverride = $_POST['bdprss_csitenameoverride'];	
	if($csitenameoverride != 'Y') $csitenameoverride = 'N';
	$siteArray['csitenameoverride'] = $csitenameoverride;

	$siteArray['cidentifier'] = $_POST['bdprss_cidentifier'];
	$siteArray['csitename'] = BDPRSSFeed::title_recode($_POST['bdprss_csitename']);
	$siteArray['csiteurl'] = BDPRSSFeed::title_recode($_POST['bdprss_csiteurl']);
	$siteArray['cdescription'] = mb_substr(BDPRSSFeed::title_recode($_POST['bdprss_cdescription']), 0, 250);
	$siteArray['cgmtadjust'] = floatval($_POST['bdprss_cgmtadjust']);

	$cpollingfreqmins = intval($_POST['bdprss_cpollingfreqmins']);
	if($cpollingfreqmins < 0 || $cpollingfreqmins > 9999) $cpollingfreqmins = 0;
	$siteArray['cpollingfreqmins'] =  $cpollingfreqmins ;

	$bdprss_db->updateTable($bdprss_db->sitetable, $siteArray, 'cidentifier');
}

if( isset($_POST['bdprss_edit_list_button']) )
{
	$listArray = array();
	
	$listArray['lidentifier'] = $_POST['bdprss_lidentifier'];
	$listArray['lname'] = htmlspecialchars($_POST['bdprss_lname']);
	$listArray['ltype'] = $_POST['bdprss_ltype'];

	// ints
	$litemspersite = intval($_POST['bdprss_litemspersite']);
	if($litemspersite < 0) $litemspersite = 0;
	$listArray['litemspersite'] = $litemspersite;

	$lmaxlength = intval($_POST['bdprss_lmaxlength']);
	if($lmaxlength < 0) $lmaxlength = 0;
	$listArray['lmaxlength'] = $lmaxlength;

	$lmaxwordlength = intval($_POST['bdprss_lmaxwordlength']);
	if($lmaxwordlength < 0) $lmaxwordlength = 0;
	$listArray['lmaxwordlength'] = $lmaxwordlength;

	// booleans
	$lage = $_POST['bdprss_lage'];						if($lage != 'Y') $lage = 'N';
	$lshort = $_POST['bdprss_lshort'];					if($lshort != 'Y') $lshort = 'N';
	$llistall = $_POST['bdprss_llistall'];				if($llistall != 'Y') $llistall = 'N';
	$loutputsitename = $_POST['bdprss_loutputsitename'];if($loutputsitename != 'Y') $loutputsitename = 'N';
	$llaunchnew = $_POST['bdprss_llaunchnew'];			if($llaunchnew != 'Y') $llaunchnew = 'N';

	$listArray['lage'] = $lage;
	$listArray['lshort'] = $lshort;
	$listArray['llistall'] = $llistall;
	$listArray['loutputsitename'] = $loutputsitename;
	$listArray['llaunchnew'] = $llaunchnew;

	$lurls = '';
	$result = $bdprss_db->get_all_sites();
	if($result) 
	{
		$subsequent = false;
		foreach($result as $r) 
		{
			$id = $r->{$bdprss_db->cidentifier};
			$url = $r->{$bdprss_db->cfeedurl};
			
			$feed = 'bdprss_feed_' . $id;
			if( $_POST[$feed] ) 
			{
				if($subsequent) $lurls .= ',';
				$subsequent = true;
				$lurls .= $id;
			}
		}
	}
	$listArray['lurls'] = $lurls;

	$listArray['lprefeed'] = $_POST['bdprss_lprefeed'];
	$listArray['lpostfeed'] = $_POST['bdprss_lpostfeed'];

	$listArray['lpresite'] = $_POST['bdprss_lpresite'];
	$listArray['lpostsite'] = $_POST['bdprss_lpostsite'];

	$listArray['lpresitename'] = $_POST['bdprss_lpresitename'];
	$listArray['lpostsitename'] = $_POST['bdprss_lpostsitename'];

	$listArray['lpreitemset'] = $_POST['bdprss_lpreitemset'];
	$listArray['lpostitemset'] = $_POST['bdprss_lpostitemset'];

	$listArray['lpreitem'] = $_POST['bdprss_lpreitem'];
	$listArray['lpostitem'] = $_POST['bdprss_lpostitem'];

	$listArray['lpreitemname'] = $_POST['bdprss_lpreitemname'];
	$listArray['lpostitemname'] = $_POST['bdprss_lpostitemname'];

	$listArray['lpreitembody'] = $_POST['bdprss_lpreitembody'];
	$listArray['lpostitembody'] = $_POST['bdprss_lpostitembody'];

	$listArray['lpreitemage'] = $_POST['bdprss_lpreitemage'];
	$listArray['lpostitemage'] = $_POST['bdprss_lpostitemage'];

	$listArray['lbetwixtsiteitem'] = $_POST['bdprss_lbetwixtsiteitem'];

	$listArray['lprearchivelist'] = $_POST['bdprss_lprearchivelist'];
	$listArray['lpostarchivelist'] = $_POST['bdprss_lpostarchivelist'];

	$listArray['lprearchivedate'] = $_POST['bdprss_lprearchivedate'];
	$listArray['lpostarchivedate'] = $_POST['bdprss_lpostarchivedate'];

	$lallowablexhtmltags = '';
	$first = TRUE;
	foreach($bdprssTagSet as $key => $value)
	{
		$tag = BDPRSS2::tagalise($key);
		if(isset($_POST[$tag]))
		{
			if(!$first) $lallowablexhtmltags .= ',';
			$lallowablexhtmltags .= $key;
			$first = FALSE;
		}
	}
	$listArray['lallowablexhtmltags'] = $lallowablexhtmltags;
	
//	$listArray['larchivedateformat'] = $_POST['bdprss_larchivedateformat'];
//	$listArray['larchiveweeks'] = intval($_POST['bdprss_larchiveweeks']);
	$listArray['larchiveviewpage'] = $_POST['bdprss_larchiveviewpage'];
	$listArray['lprearchivelist'] = $_POST['bdprss_lprearchivelist'];
	$listArray['lpostarchivelist'] = $_POST['bdprss_lpostarchivelist'];
	$listArray['lprearchivedate'] = $_POST['bdprss_lprearchivedate'];
	$listArray['lpostarchivedate'] = $_POST['bdprss_lpostarchivedate'];

	$listArray['liscachable'] = $_POST['bdprss_liscachable']; 
	if($listArray['liscachable'] != 'Y') $listArray['liscachable'] = 'N';
	$listArray['lcacheviewpage'] = $_POST['bdprss_lcacheviewpage'];
	$listArray['lprecacheitem'] = $_POST['bdprss_lprecacheitem'];
	$listArray['lpostcacheitem'] = $_POST['bdprss_lpostcacheitem'];
	$listArray['lprecachetitle'] = $_POST['bdprss_lprecachetitle'];
	$listArray['lpostcachetitle'] = $_POST['bdprss_lpostcachetitle'];
	$listArray['lprecachesite'] = $_POST['bdprss_lprecachesite'];
	$listArray['lpostcachesite'] = $_POST['bdprss_lpostcachesite'];
	$listArray['lprecacheitemtime'] = $_POST['bdprss_lprecacheitemtime'];
	$listArray['lpostcacheitemtime'] = $_POST['bdprss_lpostcacheitemtime'];
	$listArray['lprecacheupdatetime'] = $_POST['bdprss_lprecacheupdatetime'];
	$listArray['lpostcacheupdatetime'] = $_POST['bdprss_lpostcacheupdatetime'];

	$listArray['lcanrss'] = $_POST['bdprss_lcanrss']; 
	if($listArray['lcanrss'] != 'Y') $listArray['lcanrss'] = 'N';
	$listArray['lrssfullorsummary'] = $_POST['bdprss_lrssfullorsummary'];
	if($listArray['lrssfullorsummary'] != 'FULL') $listArray['lrssfullorsummary'] = 'SUMMARY';
	$listArray['lrssnumbersingle'] = intval($_POST['bdprss_lrssnumbersingle']);
	if($listArray['lrssnumbersingle'] < 0 || $listArray['lrssnumbersingle'] > 99999) 
		$listArray['lrssnumbersingle'] = 0;
	$listArray['lrsslink'] = $_POST['bdprss_lrsslink'];
	$listArray['lrssdescription'] = $_POST['bdprss_lrssdescription'];

	$bdprss_db->updateTable($bdprss_db->listtable, $listArray, 'lidentifier');
}


/* ----- Capture and process calling arguments ----- */

$argumentSet = array('action', 'rss', 'list');
for ($i = 0;  $i < count($argumentSet);  $i++) 
{
	$variable = $argumentSet[$i];
	if (!isset($$variable)) // don't override if already set
	{
		if (!empty($_POST[$variable]))
			$$variable = $_POST[$variable];
		elseif (!empty($_GET[$variable]))
			$$variable = $_GET[$variable];
		else
			$$variable = '';
	}
}

$editlist = false;
$editfeed = false;
$errorlist = false;

switch($action) 
{
	case 'update':
		$r = $bdprss_db->get_site_by_id($rss);
		if($r) BDPRSS2::update($r);
	break;

	case 'delete':
		$bdprss_db->deleteFeed($rss);
	break;

	case 'createlist':
		$list = $bdprss_db->createlist();
		$editlist = true;
		// no break - flows into editlist

	case 'editlist':
		$editlist = true;
	break;

	case 'errorlist':
		$errorlist = true;
	break;

	case 'errordelete':
		$bdprss_db->deleteErrorTable($rss);
	break;

	case 'editfeed':
		$editfeed = true;
	break;

	case 'dellist':
		if($list) $bdprss_db->deletelist($list);
	break;
}

$selfreference = get_settings('siteurl') . '/wp-admin/edit.php?page=' .BDPRSS2_DIRECTORY. '/bdp-rssadmin.php';


/* ----- Drop in the appropriate administration page ----- */

echo "<div class='wrap'>\n";

if ($editlist && $list) 
{
	include (dirname(__FILE__)."/bdp-rssadmin-edit.php");
} 
elseif ($editfeed && $rss) 
{
	include (dirname(__FILE__)."/bdp-rssadmin-sno.php");
} 
elseif ($errorlist) 
{
	include (dirname(__FILE__)."/bdp-rssadmin-error.php");
}
else
	include (dirname(__FILE__)."/bdp-rssadmin-general.php");

echo '<p align="center">&nbsp;<br />This page was brought to you by the<br />'.
	'<a href="http://www.ozpolitics.info/blog/?p=87">'.
	'<strong>' .BDPRSS2_PRODUCT. ' version ' .BDPRSS2_VERSION. "</strong></a></p>\n";

echo "</div>\n";
?>
