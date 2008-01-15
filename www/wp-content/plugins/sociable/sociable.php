<?php
/*
Plugin Name: Sociable
Plugin URI: http://push.cx/sociable
Description: Automatically add links on your posts to popular <a href="http://www.maxpower.ca/bookmarking">social bookmarking sites</a>. Go to Options -> Sociable for setup.
Version: 2.0
Author: Peter Harkins
Author URI: http://push.cx
*/
$sociable_version = '2.0'; // url-safe version string
$sociable_date = '2007-02-02'; // date this version was released, beats a version #

/*
Copyright 2006 Peter Harkins (ph@malaprop.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

load_plugin_textdomain('sociable', 'wp-content/plugins/sociable/i18n');

$sociable_known_sites = Array(

	'blinkbits' => Array(
		'favicon' => 'blinkbits.png',
		'url' => 'http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;source_url=PERMALINK&amp;title=TITLE&amp;body=TITLE',
	),

	'BlinkList' => Array(
		'favicon' => 'blinklist.png',
		'url' => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=PERMALINK&amp;Title=TITLE',
	        'description' => 'Description',
	),

	'BlogMemes' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.net/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'BlogMemes Fr' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.fr/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'BlogMemes Sp' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.com/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'BlogMemes Cn' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.cn/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'BlogMemes Jp' => Array(
		'favicon' => 'blogmemes.png',
		'url' => 'http://www.blogmemes.jp/post.php?url=PERMALINK&amp;title=TITLE',
	),

	'blogmarks' => Array(
		'favicon' => 'blogmarks.png',
		'url' => 'http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=PERMALINK&amp;title=TITLE',
	),

	'blogtercimlap' => Array(
		'favicon' => 'blogter.png',
		'url' => 'http://cimlap.blogter.hu/index.php?action=suggest_link&title=TITLE&amp;url=PERMALINK',
	),

	'Blue Dot' => Array(
		'favicon' => 'bluedot.png',
		'url' => 'http://bluedot.us/Authoring.aspx?>u=PERMALINK&amp;title=TITLE',
	),

	'Book.mark.hu' => Array(
		'favicon' => 'bookmarkhu.png',
		'url' => 'http://book.mark.hu/bookmarks.php/?action=add&amp;address=PERMALINK%2F&amp;title=TITLE',
                'description' => 'description',
	),

	'Bumpzee' => Array(
		'favicon' => 'bumpzee.png',
		'url' => 'http://www.bumpzee.com/bump.php?u=PERMALINK',
	),

	'co.mments' => Array(
		'favicon' => 'co.mments.gif',
		'url' => 'http://co.mments.com/track?url=PERMALINK&amp;title=TITLE',
	),

	'connotea' => Array(
		'favicon' => 'connotea.png',
		'url' => 'http://www.connotea.org/addpopup?continue=confirm&amp;uri=PERMALINK&amp;title=TITLE',
	),


	'del.icio.us' => Array(
		'favicon' => 'delicious.png',
		'url' => 'http://del.icio.us/post?url=PERMALINK&amp;title=TITLE',
	),

	'De.lirio.us' => Array(
		'favicon' => 'delirious.png',
		'url' => 'http://de.lirio.us/rubric/post?uri=PERMALINK;title=TITLE;when_done=go_back',
	),

	'Digg' => Array(
		'favicon' => 'digg.png',
		'url' => 'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE',
		'description' => 'bodytext',
	),

	'DotNetKicks' => Array(
		'favicon' => 'dotnetkicks.png',
		'url' => 'http://www.dotnetkicks.com/kick/?url=PERMALINK&amp;title=TITLE',
		'description' => 'description',
	),

	'DZone' => Array(
		'favicon' => 'dzone.png',
		'url' => 'http://www.dzone.com/links/add.html?url=PERMALINK&amp;title=TITLE',
		'description' => 'description',
	),

	'Fark' => Array(
		'favicon' => 'fark.png',
		'url' => 'http://cgi.fark.com/cgi/fark/edit.pl?new_url=PERMALINK&amp;new_comment=TITLE&amp;new_comment=BLOGNAME&amp;linktype=Misc',
		// To post to a different category, see the drop-down box labeled "Link Type" at
		// http://cgi.fark.com/cgi/fark/submit.pl for a complete list
	),

	'feedmelinks' => Array(
		'favicon' => 'feedmelinks.png',
		'url' => 'http://feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;url=PERMALINK&amp;name=TITLE',
	),

	'Furl' => Array(
		'favicon' => 'furl.png',
		'url' => 'http://www.furl.net/storeIt.jsp?u=PERMALINK&amp;t=TITLE',
	),

	'Fleck' => Array(
		'favicon' => 'fleck.gif',
		'url' => 'http://extension.fleck.com/?v=b.0.804&amp;url=PERMALINK',
	),

	'Gwar' => Array(
		'favicon' => 'gwar.gif',
		'url' => 'http://www.gwar.pl/DodajGwar.html?u=PERMALINK',
	),

	'Haohao' => Array(
		'favicon' => 'haohao.png',
		'url' => 'http://www.haohaoreport.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),

	'Hemidemi' => Array(
		'favicon' => 'hemidemi.png',
		'url' => 'http://www.hemidemi.com/user_bookmark/new?title=TITLE&amp;url=PERMALINK',
	),

	'IndiaGram' => Array(
		'favicon' => 'indiagram.png',
		'url' => 'http://www.indiagram.com/mock/bookmarks/desitrain?action=add&amp;address=PERMALINK&amp;title=TITLE',
	),

	'IndianPad' => Array(
		'favicon' => 'indianpad.png',
		'url' => 'http://www.indianpad.com/submit.php?url=PERMALINK',
	),

	'Internetmedia' => Array(
		'favicon' => 'im.png',
		'url' => 'http://internetmedia.hu/submit.php?url=PERMALINK'
	),

	'kick.ie' => Array(
		'favicon' => 'kickit.png',
		'url' => 'http://kick.ie/submit/?url=PERMALINK&amp;title=TITLE',
	),

	'LinkaGoGo' => Array(
		'favicon' => 'linkagogo.png',
		'url' => 'http://www.linkagogo.com/go/AddNoPopup?url=PERMALINK&amp;title=TITLE',
	),

	'Linkter' => Array(
		'favicon' => 'linkter.png',
		'url' => 'http://www.linkter.hu/index.php?action=suggest_link&amp;url=PERMALINK&amp;title=TITLE',
	),

	'Ma.gnolia' => Array(
		'favicon' => 'magnolia.png',
		'url' => 'http://ma.gnolia.com/bookmarklet/add?url=PERMALINK&amp;title=TITLE',
	),

	'MisterWong' => Array(
		'favicon' => 'misterwong.gif',
		'url' => 'http://www.mister-wong.com/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=soc',
	),

	'MyShare' => Array(
		'favicon' => 'myshare.png',
		'url' => 'http://myshare.url.com.tw/index.php?func=newurl&amp;url=PERMALINK&amp;desc=TITLE',
	),

	'NewsVine' => Array(
		'favicon' => 'newsvine.png',
		'url' => 'http://www.newsvine.com/_tools/seed&amp;save?u=PERMALINK&amp;h=TITLE',
	),

	'Netscape' => Array(
		'favicon' => 'netscape.gif',
		'url' => 'http://www.netscape.com/submit/?U=PERMALINK&amp;T=TITLE',
	),

	'Netvouz' => Array(
		'favicon' => 'netvouz.png',
		'url' => 'http://www.netvouz.com/action/submitBookmark?url=PERMALINK&amp;title=TITLE&amp;popup=no',
	),

	'PlugIM' => Array(
		'favicon' => 'plugim.png',
		'url' => 'http://www.plugim.com/submit?url=PERMALINK&amp;title=TITLE',
	),

	'PopCurrent' => Array(
		'favicon' => 'popcurrent.png',
		'url' => 'http://popcurrent.com/submit?url=PERMALINK&amp;title=TITLE&amp;rss=RSS',
                'description' => 'description',
	),

	'ppnow' => Array(
		'favicon' => 'ppnow.png',
		'url' => 'http://www.ppnow.net/submit.php?url=PERMALINK',
	),

	'RawSugar' => Array(
		'favicon' => 'rawsugar.png',
		'url' => 'http://www.rawsugar.com/tagger/?turl=PERMALINK&amp;tttl=TITLE',
	),

	'Rec6' => Array(
		'favicon' => 'rec6.gif',
		'url' => 'http://www.syxt.com.br/rec6/link.php?url=PERMALINK&amp;=TITLE',
	),

	'Reddit' => Array(
		'favicon' => 'reddit.png',
		'url' => 'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
	),

	'Scoopeo' => Array(
		'favicon' => 'scoopeo.png',
		'url' => 'http://www.scoopeo.com/scoop/new?newurl=PERMALINK&amp;title=TITLE',
	),	

	'scuttle' => Array(
		'favicon' => 'scuttle.png',
		'url' => 'http://www.scuttle.org/bookmarks.php/maxpower?action=add&amp;address=PERMALINK&amp;title=TITLE',
                'description' => 'description',
	),

	'Shadows' => Array(
		'favicon' => 'shadows.png',
		'url' => 'http://www.shadows.com/features/tcr.htm?url=PERMALINK&amp;title=TITLE',
	),

	'Simpy' => Array(
		'favicon' => 'simpy.png',
		'url' => 'http://www.simpy.com/simpy/LinkAdd.do?href=PERMALINK&amp;title=TITLE&amp;src=sociable-VERSION',
	),

	'Slashdot' => Array(
		'favicon' => 'slashdot.png',
		'url' => 'http://slashdot.org/bookmark.pl?title=TITLE&amp;url=PERMALINK',
	),

	'Smarking' => Array(
		'favicon' => 'smarking.png',
		'url' => 'http://smarking.com/editbookmark/?url=PERMALINK&amp;title=TITLE',
	),

	'Spurl' => Array(
		'favicon' => 'spurl.png',
		'url' => 'http://www.spurl.net/spurl.php?url=PERMALINK&amp;title=TITLE',
	),

	'SphereIt' => Array(
		'favicon' => 'sphere.png',
		'url' => 'http://www.sphere.com/search?q=sphereit:PERMALINK&amp;title=TITLE',
	),

	'StumbleUpon' => Array(
		'favicon' => 'stumbleupon.png',
		'url' => 'http://www.stumbleupon.com/url/PERMALINK',
	),

	'Taggly' => Array(
		'favicon' => 'taggly.png',
		'url' => 'http://taggly.com/bookmarks.php/pass?action=add&amp;address=',
	),

	'Technorati' => Array(
		'favicon' => 'technorati.png',
		'url' => 'http://technorati.com/faves?add=PERMALINK',
	),

	'TailRank' => Array(
		'favicon' => 'tailrank.png',
		'url' => 'http://tailrank.com/share/?text=&amp;link_href=PERMALINK&amp;title=TITLE',
	),

	'ThisNext' => Array(
		'favicon' => 'thisnext.png',
		'url' => 'http://www.thisnext.com/pick/new/submit/sociable/?url=PERMALINK&amp;name=TITLE',
	),

	'Webride' => Array(
		'favicon' => 'webride.png',
		'url' => 'http://webride.org/discuss/split.php?uri=PERMALINK&amp;title=TITLE',
	),

	'Wists' => Array(
		'favicon' => 'wists.png',
		'url' => 'http://wists.com/s.php?c=&amp;r=PERMALINK&amp;title=TITLE',
		'class' => 'wists',
	),

	'Wykop' => Array(
		'favicon' => 'wykop.gif',
		'url' => 'http://www.wykop.pl/dodaj?url=PERMALINK',
	),

	'YahooMyWeb' => Array(
		'favicon' => 'yahoomyweb.png',
		'url' => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?u=PERMALINK&amp;=TITLE',
	),

);

$sociable_files = Array(
	'description_selection.js',
	'sociable-admin.css',
	'sociable.css',
	'sociable.php',
	'sociable.css',
	'sociable-admin.css',
        'wists.js',
	'images/',
	'images/blinkbits.png',
	'images/blinklist.png',
	'images/blogmarks.png',
	'images/blogmemes.png',
	'images/blogter.png',
	'images/bluedot.png',
	'images/bookmarkhu.png',
	'images/bumpzee.png',
	'images/co.mments.gif',
	'images/connotea.png',
	'images/delicious.png',
	'images/delirious.png',
	'images/digg.png',
	'images/dotnetkicks.png',
	'images/dzone.png',
	'images/fark.png',
	'images/feedmelinks.png',
	'images/fleck.gif',
	'images/furl.png',
	'images/gwar.gif',
	'images/haohao.png',
	'images/hemidemi.png',
	'images/im.png',
	'images/indiagram.png',
	'images/indianpad.png',
	'images/kickit.png',
	'images/linkagogo.png',
	'images/linkter.png',
	'images/linkter.png',
	'images/magnolia.png',
	'images/misterwong.gif',
	'images/myshare.png',
	'images/netscape.gif',
	'images/netvouz.png',
	'images/newsvine.png',
	'images/plugim.png',
	'images/popcurrent.png',
	'images/ppnow.png',
	'images/rawsugar.png',
	'images/rec6.gif',
	'images/reddit.png',
	'images/scoopeo.png',
	'images/scuttle.png',
	'images/shadows.png',
	'images/simpy.png',
	'images/slashdot.png',
	'images/smarking.png',
	'images/sphere.png',
	'images/spurl.png',
	'images/stumbleupon.png',
	'images/taggly.png',
	'images/tailrank.png',
	'images/technorati.png',
	'images/webride.png',
	'images/wists.png',
	'images/wykop.gif',
	'images/yahoomyweb.png',
	'tool-man/',
	'tool-man/coordinates.js',
	'tool-man/core.js',
	'tool-man/css.js',
	'tool-man/drag.js',
	'tool-man/dragsort.js',
	'tool-man/events.js',
);


function sociable_html($display=Array()) {
	global $sociable_known_sites, $sociable_version;
	$active_sites = get_option('sociable_active_sites');

	$html = "";

	$imagepath = get_bloginfo('wpurl') . '/wp-content/plugins/sociable/images/';

	// if no sites are specified, display all active
	// have to check $active_sites has content because WP
	// won't save an empty array as an option
	if (empty($display) and $active_sites)
		$display = $active_sites;
	// if no sites are active, display nothing
	if (empty($display))
		return "";

	// Load the post's data
	$blogname = urlencode(get_bloginfo('wpurl'));
	global $wp_query; 
	$post = $wp_query->post;
	$permalink = urlencode(get_permalink($post->ID));
	$title = urlencode($post->post_title);
	$rss = urlencode(get_bloginfo('ref_url'));

	$html .= "\n<div class=\"sociable\">\n<span class=\"sociable_tagline\">\n";
	$html .= get_option("sociable_tagline");
	$html .= "\n\t<span>" . __("These icons link to social bookmarking sites where readers can share and discover new web pages.", 'sociable') . "</span>";
	$html .= "\n</span>\n<ul>\n";

	foreach($display as $sitename) {
		// if they specify an unknown or inactive site, ignore it
		if (!in_array($sitename, $active_sites))
			continue;

		$site = $sociable_known_sites[$sitename];
		$html .= "\t<li>";

		$url = $site['url'];
		$url = str_replace('PERMALINK', $permalink, $url);
		$url = str_replace('TITLE', $title, $url);
		$url = str_replace('RSS', $rss, $url);
		$url = str_replace('BLOGNAME', $blogname, $url);
		$url = str_replace('VERSION', $sociable_version, $url);

		$html .= "<a href=\"$url\" title=\"$sitename\"";
		if ($site['description'])
                    $html .= " onfocus=\"sociable_description_link(this, '{$site['description']}')\"";
                $html .= ">";
		$html .= "<img src=\"$imagepath{$site['favicon']}\" title=\"$sitename\" alt=\"$sitename\" class=\"sociable-hovers";
                if ($site['class'])
                    $html .= " sociable_{$site['class']}";
                $html .= "\" />";
		$html .= "</a></li>\n";
	}

	$html .= "</ul>\n</div>\n";

	return $html;
}

// Hook the_content to output html if we should display on any page
$sociable_contitionals = get_option('sociable_conditionals');
if (is_array($sociable_contitionals) and in_array(true, $sociable_contitionals)) {
	add_filter('the_content', 'sociable_display_hook');
	add_filter('the_excerpt', 'sociable_display_hook');
	
	function sociable_display_hook($content='') {
		$conditionals = get_option('sociable_conditionals');
		if ((is_home()     and $conditionals['is_home']) or
		    (is_single()   and $conditionals['is_single']) or
		    (is_page()     and $conditionals['is_page']) or
		    (is_category() and $conditionals['is_category']) or
		    (is_date()     and $conditionals['is_date']) or
		    (is_search()   and $conditionals['is_search']) or
		     0)
			$content .= sociable_html();
	
		return $content;
	}
}

// Hook wp_head to add css
add_action('wp_head', 'sociable_wp_head');
function sociable_wp_head() {
	if (in_array('Wists', get_option('sociable_active_sites')))
		echo '<script language="JavaScript" type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sociable/wists.js"></script>';

        echo '<script language="JavaScript" type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/sociable/description_selection.js"></script>';
	echo '<link rel="stylesheet" type="text/css" media="screen" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/sociable/sociable.css" />';
}

// load wp rss functions for update checking.
if (!function_exists('parse_w3cdtf')) {
	require_once(ABSPATH . WPINC . '/rss-functions.php');
}

// Plugin config/data setup
if (function_exists('register_activation_hook')) {
	// for WP 2
	register_activation_hook(__FILE__, 'sociable_activation_hook');
} else {
	// for WP 1.5, which doesn't have any activation hook
	if (!is_array(get_option('sociable_active_sites')))
		sociable_activation_hook();
}
function sociable_activation_hook() {
	return sociable_restore_config(False);
}

// restore built-in defaults, optionally overwriting existing values
function sociable_restore_config($force=False) {
	// Load defaults, taking care not to smash already-set options
	global $sociable_known_sites;

	// Used to store sites in the db with the idea users would
	// add sites, but nobody will so I dropped the feature.
	// This should clean up any old installs.
	delete_option('sociable_known_sites');

	// active_sites defaults to the Sociable sponsors
	if ($force or !is_array(get_option('sociable_active_sites')))
		update_option('sociable_active_sites', array(
			'Digg',
			'del.icio.us',
			'Netvouz',
			'DZone',
			'ThisNext',
			'MisterWong',
			'Wists',
		));

	// tagline defaults to a Hitchiker's Guide to the Galaxy reference
	if ($force or !is_string(get_option('sociable_tagline')))
		update_option('sociable_tagline', "<strong>" . __("Share and Enjoy:", 'sociable') . "</strong>");

	// only display on single posts and pages by default
	if ($force or !is_array(get_option('sociable_conditionals')))
		update_option('sociable_conditionals', array(
			'is_home' => False,
			'is_single' => True,
			'is_page' => True,
			'is_category' => False,
			'is_date' => False,
			'is_search' => False,
		));

	// last-updated date defaults to 0000-00-00
	// this is to trigger the update check on first run
	if ($force or !get_option('sociable_updated'))
		update_option('sociable_updated', '0000-00-00');
}

// Hook the admin_menu display to add admin page
add_action('admin_menu', 'sociable_admin_menu');
function sociable_admin_menu() {
	add_submenu_page('options-general.php', 'Sociable', 'Sociable', 8, 'Sociable', 'sociable_submenu');
}

// Admin page header
add_action('admin_head', 'sociable_admin_head');
function sociable_admin_head() {
?>

<!-- The ToolMan lib provides drag and drop: http://tool-man.org/examples/sorting.html -->
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/tool-man/core.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/tool-man/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/tool-man/css.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/tool-man/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/tool-man/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/tool-man/events.js"></script>
<script language="JavaScript" type="text/javascript"><!--
var dragsort = ToolMan.dragsort();
var junkdrawer = ToolMan.junkdrawer();

function save_reorder(id) {
	site_order = document.getElementById('site_order');

	old_order = site_order.value;
	new_order = junkdrawer.serializeList(document.getElementById('sociable_site_list'));
	site_order.value = new_order;

	if (!site_order.used || new_order == old_order)
		toggle_checkbox(id);
	site_order.used = true;
}

/* make checkbox action prettier */
function toggle_checkbox(id) {
	var checkbox = document.getElementById(id);

	checkbox.checked = !checkbox.checked;
	if (checkbox.checked)
		checkbox.parentNode.className = 'active';
	else
		checkbox.parentNode.className = 'inactive';
}
--></script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/sociable/sociable-admin.css" />
<?php
}

function sociable_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}

function sociable_update_version() {
	global $sociable_date;

	$known_update = get_option('sociable_known_update');
	$found_update = $known_update;

	// check for new versions if it's been a week
	if (date("Y-m-d", time() + 7 * 24 * 60 * 60) > get_option('sociable_updated')) {
                // collects only publicly-available stats
                $stats = Array(
                    'php'     => PHP_VERSION,
                    'server'  => $_SERVER['SERVER_SOFTWARE'],
                    'blog'    => 'Wordpress',
                    'version' => get_bloginfo('version'),
                    'url'     => get_bloginfo('wpurl'),
                    'sites'   => implode(',', get_option('sociable_active_sites')),
                    'locale'  => WPLANG,
                );
                $args = array();
                foreach($stats as $key => $value) {
                    $args[] = $key . '=' . urlencode($value);
                }
                $args = implode('&', $args);

		// note the updating and fetch potential updates
		update_option('sociable_updated', date("Y-m-d"));
		$update = fetch_rss("http://push.cx/tag/sociable/feed?$args");

		if ($update === False) {
			sociable_message(__('Sociable tried to check for updates but failed. This might be the way PHP is set up, or just random network issues. Please <a href="http://push.cx/sociable">visit the Sociable website</a> to update manually if needed.', 'sociable'));
			return;
		}

		// loop through feed, pulling out any updates
		foreach($update->items as $item) {
			$updates = Array();
			if (preg_match('|<!-- Sociable:Update date="(\d{4}-\d{2}-\d{2})" -->|', $item['content']['encoded'], $updates)) {
				// if this is the newest update, save it
				if ($updates[1] > $found_update)
					$found_update = $updates[1];
			}
		}
	}

	// if an newer update was found, save it
	if ($found_update > $known_update)
		update_option('sociable_known_update', $found_update);

	// if the best-known update is newer than this ver, tell user
	if ($found_update > $sociable_date)
		sociable_message(__('A <a href="http://push.cx/sociable">new version of Sociable is available</a>', 'sociable') . ' (' . __('as of ', 'sociable') . $found_update . ').');
}

// Sanity check the upload worked
function sociable_upload_errors() {
	global $sociable_files;

	$cwd = getcwd(); // store current dir for restoration
	if (!@chdir('../wp-content/plugins'))
		return __("Couldn't find wp-content/plugins folder. Please make sure WordPress is installed correctly.", 'sociable');
	if (!is_dir('sociable'))
		return __("Can't find sociable folder.", 'sociable');
	chdir('sociable');

	foreach($sociable_files as $file) {
		if (substr($file, -1) == '/') {
			if (!is_dir(substr($file, 0, strlen($file) - 1)))
				return __("Can't find folder:", 'sociable') . " <kbd>$file</kbd>";
		} else if (!is_file($file))
		return __("Can't find file:", 'sociable') . " <kbd>$file</kbd>";
	}


	$header_filename = '../../themes/' . get_option('template') . '/header.php';
	if (!file_exists($header_filename) or strpos(@file_get_contents($header_filename), 'wp_head()') === false)
		return __("Your theme isn't set up for Sociable to load its style. Please edit <kbd>header.php</kbd> and add a line reading <kbd>&lt?php wp_head(); ?&gt;</kbd> before <kbd>&lt;/head&gt;</kbd> to fix this.", 'sociable');

	chdir($cwd); // restore cwd

	return false;
}

// The admin page
function sociable_submenu() {
	global $sociable_known_sites, $sociable_date, $sociable_files;

	// update options in db if requested
	if ($_REQUEST['restore']) {
		sociable_restore_config(True);
	sociable_message(__("Restored all settings to defaults.", 'sociable'));
	} else if ($_REQUEST['save']) {
		// update active sites
		$active_sites = Array();
		if (!$_REQUEST['active_sites'])
			$_REQUEST['active_sites'] = Array();
		foreach($_REQUEST['active_sites'] as $sitename=>$dummy)
			$active_sites[] = $sitename;
		update_option('sociable_active_sites', $active_sites);
		// have to delete and re-add because update doesn't hit the db for identical arrays
		// (sorting does not influence associated array equality in PHP)
		delete_option('sociable_active_sites', $active_sites);
		add_option('sociable_active_sites', $active_sites);

		// update conditional displays
		$conditionals = Array();
		if (!$_REQUEST['conditionals'])
			$_REQUEST['conditionals'] = Array();
		foreach(get_option('sociable_conditionals') as $condition=>$toggled)
			$conditionals[$condition] = array_key_exists($condition, $_REQUEST['conditionals']);
		update_option('sociable_conditionals', $conditionals);

		// update tagline
		if (!$_REQUEST['tagline'])
			$_REQUEST['tagline'] = "";
		update_option('sociable_tagline', $_REQUEST['tagline']);
		
		sociable_message(__("Saved changes.", 'sociable'));
	}

	if ($str = sociable_upload_errors())
		sociable_message("$str</p><p>" . __("In your plugins/sociable folder, you must have these files:", 'sociable') . ' <pre>' . implode("\n", $sociable_files) ); 
	sociable_update_version();

	// show active sites first and in order
	$active_sites = get_option('sociable_active_sites');
	$active = Array(); $disabled = $sociable_known_sites;
	foreach($active_sites as $sitename) {
		$active[$sitename] = $disabled[$sitename];
		unset($disabled[$site]);
	}
	uksort($disabled, "strnatcasecmp");

	// load options from db to display
	$tagline = get_option('sociable_tagline');
	$conditionals = get_option('sociable_conditionals');
	$updated = get_option('sociable_updated');

	// display options
?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

<div class="wrap" id="sociable_options">
<fieldset id="sociable_sites">

<h3><?php _e("Sociable Options", 'sociable'); ?></h3>

<p><?php _e("Drag and drop sites to reorder them. Only the sites you check will appear publicly.", 'sociable'); ?></p>

<ul id="sociable_site_list">
<?php foreach (array_merge($active, $disabled) as $sitename=>$site) { ?>
	<li
		id="<?php echo $sitename; ?>"
		class="sociable_site <?php echo (in_array($sitename, $active_sites)) ? "active" : "inactive"; ?>"
		onmouseup="javascript:save_reorder('cb_<?php echo $sitename; ?>');"
	>
		<input
			type="checkbox"
			id="cb_<?php echo $sitename; ?>"
			class="checkbox"
			name="active_sites[<?php echo $sitename; ?>]"
			onclick="javascript:toggle_checkbox('cb_<?php echo $sitename; ?>');"
			<?php echo (in_array($sitename, $active_sites)) ? ' checked="checked"' : ''; ?>
		/>
		<img src="../wp-content/plugins/sociable/images/<?php echo $site['favicon']?>" width="16" height="16" alt="" />
		<?php print $sitename; ?>
	</li>
<?php } ?>
</ul>
<input type="hidden" id="site_order" name="site_order" value="<?php echo join('|', array_keys($sociable_known_sites)) ?>" />
<script language="JavaScript" type="text/javascript"><!--
	dragsort.makeListSortable(document.getElementById("sociable_site_list"));
--></script>

</fieldset>
<div style="clear: left; display: none;"><br/></div>

<fieldset id="sociable_tagline">
<p>
<?php _e("Change the text displayed in front of the icons below. For complete customization, edit <kbd>sociable.css</kbd> in the Sociable plugin directory.", 'sociable'); ?>
</p>
<input type="text" name="tagline" value="<?php echo htmlspecialchars($tagline); ?>" />
</fieldset>


<fieldset id="sociable_conditionals">
<p><?php _e("The icons appear at the end of each blog post, and posts may show on many different types of pages. Depending on your theme and audience, it may be tacky to display icons on all types of pages.", 'sociable'); ?></p>

<ul style="list-style-type: none">
	<li><input type="checkbox" name="conditionals[is_home]"<?php echo ($conditionals['is_home']) ? ' checked="checked"' : ''; ?> /> <?php _e("Front page of the blog", 'sociable'); ?></li>
	<li><input type="checkbox" name="conditionals[is_single]"<?php echo ($conditionals['is_single']) ? ' checked="checked"' : ''; ?> /> <?php _e("Individual blog posts", 'sociable'); ?></li>
	<li><input type="checkbox" name="conditionals[is_page]"<?php echo ($conditionals['is_page']) ? ' checked="checked"' : ''; ?> /> <?php _e('Individual WordPress "Pages"', 'sociable'); ?></li>
	<li><input type="checkbox" name="conditionals[is_category]"<?php echo ($conditionals['is_category']) ? ' checked="checked"' : ''; ?> /> <?php _e("Category archives", 'sociable'); ?></li>
	<li><input type="checkbox" name="conditionals[is_date]"<?php echo ($conditionals['is_date']) ? ' checked="checked"' : ''; ?> /> <?php _e("Date-based archives", 'sociable'); ?></li>
	<li><input type="checkbox" name="conditionals[is_search]"<?php echo ($conditionals['is_search']) ? ' checked="checked"' : ''; ?> /> <?php _e("Search results", 'sociable'); ?></li>
</ul>
</fieldset>

<p class="submit"><input name="save" id="save" tabindex="3" value="<?php _e("Save Changes", 'sociable'); ?>" type="submit" /></p>
<p class="submit"><input name="restore" id="restore" tabindex="3" value="<?php _e("Restore Built-in Defaults", 'sociable'); ?>" type="submit" style="border: 2px solid #e00;" /></p>

</div>

<div class="wrap">
<h3><?php _e("Automatic Updates", 'sociable'); ?></h3>
<p>
<?php _e("Sociable checks for new versions when you bring up this page. (At most once per week.)", 'sociable') ?>
</p>

<p><?php _e("This copy of Sociable is from", 'sociable'); ?> <b><?php echo $sociable_date; ?></b>.</p>
<p><?php _e("Last checked on", 'sociable'); ?> <b><?php echo $updated; ?></b>.</p>
</div>

<div class="wrap">
<p>
<?php _e('<a href="http://push.cx/sociable">Sociable</a> is copyright 2006 by <a href="http://push.cx/">Peter Harkins</a>, released under the GNU GPL version 2 or later. If you like Sociable, please send a link my way so other folks can find out about it. If you have any problems or good ideas, <a href="mailto:ph@malaprop.org">mail me</a>.', 'sociable'); ?>
</p>
</div>

</form>

<?php
}

?>
