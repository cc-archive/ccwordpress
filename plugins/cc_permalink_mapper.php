<?php
/*
Plugin Name: CC Permalink Mapper
Description: Maps old-style CC permalinks to the new-style ones
Version: 0.1
Author: Nathan Kinkade
Author URI: http://creativecommons.org
*/

/**
 * In this array go the mappings that need to happen when wordpress
 * goes to create permalinks.  The first item is a regular expression
 * that will be used to check if the permalink need rewriting, and
 * the second argument is what to replace the pattern with
 * The default is something like: /<category>/<year>/<month>/<day>/<post_id>/,
 * but we write that to something like /weblog/entry/<post_id>.  With
 * the array setup below it is possible to specify various rewrites, just
 * add them as needed.
 */
$cc_pl_rewrites = array(
	array("\/.+?\/\d{4}\/\d{2}\/\d{2}\/", "/weblog/entry/")
);

# Since we go changing it around, this variable will simply keep track
# of what the original REQUEST_URI was.
$cc_orginal_request_uri = $_SERVER['REQUEST_URI'];

add_action("init", "cc_rewrite_request_uri");
add_filter("wp_footer", "cc_rewrite_request_uri_notify");
add_filter("the_permalink", "cc_rewrite_permalink");

function cc_rewrite_request_uri() {
	if ( isset($_GET['roflcopter']) ) {
		$_SERVER['REQUEST_URI'] = "/index.php?" . rtrim($_SERVER['QUERY_STRING'], "&roflcopter");
	}
}

function cc_rewrite_permalink($link) {

	global $cc_pl_rewrites;

	foreach ( $cc_pl_rewrites as $cc_pl_rewrite ) {
		if ( preg_match("/{$cc_pl_rewrite[0]}/", $link) ) {
			$rewritten_link = preg_replace("/{$cc_pl_rewrite[0]}/", $cc_pl_rewrite[1], $link);
			return $rewritten_link;
		}
	}

}


/**
 * Drop a comment into the footer region of the page notifying
 * whoever about the change in REQUEST_URI
 */
function cc_rewrite_request_uri_notify() {

	global $cc_orginal_request_uri;

	if ( isset($_GET['roflcopter']) ) {
		echo "<!-- CC Permalink Mapper was here: $cc_orginal_request_uri -> {$_SERVER['REQUEST_URI']} -->\n";
	}

	return true;

}

?>
