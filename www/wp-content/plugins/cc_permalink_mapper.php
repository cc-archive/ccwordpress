<?php
/*
Plugin Name: CC Permalink Mapper
Description: Maps old-style permalinks to the new-style ones
Version: 0.1
Author: Nathan Kinkade
Author URI: http://creativecommons.org
*/

# Since we go changing it around, this variable will simply keep track
# of what the original REQUEST_URI was.
$orginal_request_uri = $_SERVER['REQUEST_URI'];

add_action("init", "cc_map_permalinks");
add_filter("the_permalink", "cc_rewrite_permalink");
add_filter("wp_footer", "cc_add_mapping_comment");

function cc_map_permalinks() {
	if ( isset($_GET['roflcopter']) ) {
		$_SERVER['REQUEST_URI'] = "/index.php?" . rtrim($_SERVER['QUERY_STRING'], "&roflcopter");
	}
}


/**
 * Rewrite permalinks for individual blog posts
 */
function cc_rewrite_permalink($link) {

	if ( preg_match("/\/weblog\/(.+?)\/(\d+)/", $link, $matches) ) {
		$new_link = preg_replace("/\d{4}\/\d{2}\/\d{2}/", "entry", $link);
		return $new_link;
	}

}


/**
 * Drop a comment into the footer region of the page notifying
 * whoever about the change in REQUEST_URI
 */
function cc_add_mapping_comment() {

	global $orginal_request_uri;

	if ( $original_request_uri != $_SERVER['REQUEST_URI'] ) {
		echo "<!-- CC Permalink Mapper was here: $orginal_request_uri -> {$_SERVER['REQUEST_URI']} -->\n";
	}

	return true;

}

?>
