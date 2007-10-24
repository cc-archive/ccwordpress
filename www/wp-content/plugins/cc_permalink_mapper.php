<?php
/*
Plugin Name: CC Permalink Mapper
Description: Maps old-style permalinks to the new-style ones
Version: 0.1
Author: Nathan Kinkade
Author URI: http://creativecommons.org
*/

# Absolute filesystem path of the .htaccess file in which we will find
# the special rules
$htaccess_file = "/var/www/staging.creativecommons.org/www/.htaccess";

# Since we go changing it around, this variable will simply keep track
# of what the original REQUEST_URI was.
$orginal_request_uri = $_SERVER['REQUEST_URI'];

add_action("init", "cc_map_permalinks", 1);
add_filter("wp_footer", "cc_add_mapping_comment");

/*
 * Wordpress determines what it's going to do based on the Apache
 * REQUEST_URI variable, and not always on what arguments were
 * passed to index.php.  This function will utilize some
 * mod_rewrite-like rules found in the .htaccess file in order to
 * actually alter the variable $_SERVER['REQUEST_URI'] in order to
 * nudge Wordpress into doing what we want.  Namely, to force
 * Wordpress to honor old-style permalinks that are floating around
 * the web, even though it internally uses a new style.
 */
function cc_map_permalinks() {

	global $htaccess_file;

	$mappings = load_permalink_map($htaccess_file);
	if ( count($mappings) ) {
		foreach ( $mappings as $mapping ) {
			$replacement = $mapping['replacement'];
			if ( preg_match("/{$mapping['pattern']}/", $_SERVER['REQUEST_URI'], $matches) ) {
				for ( $idx = 1; $idx < count($matches); $idx++ ) {
					$pattern = "/\\\${$idx}/";
					$replacement = preg_replace($pattern, $matches[$idx], $replacement);
				}
				$_SERVER['REQUEST_URI'] = $replacement;
				return true;
			}
		}
	}

	return false;

}


function cc_add_mapping_comment() {

	global $orginal_request_uri;

	echo "<!-- CC Permalink Mapper was here: $orginal_request_uri -> {$_SERVER['REQUEST_URI']} -->\n";

	return true;

}


/*
 * This function will load up any permalink mapping into a local array.
 * It will attempt to fetch these mappings from the .htaccess file in
 * the sites DocumentRoot.  These special rules will actually be
 * comments in the .htaccess file, but it's more convenient to locate
 * them there because that's the logical place people will go looking
 * for rules of this nature, so better there than a database table.
 */
function load_permalink_map($file) {

	$rules = array();
	$lines = file($file, FILE_IGNORE_NEW_LINES); 
	for ( $idx = 0; $idx < count($lines); $idx++ ) {
		if ( "# BEGIN CC Permalink Mapper" == $lines[$idx] ) {
			$process_line = true;
			continue;	
		} elseif ( "# END CC Permalink Mapper" == $lines[$idx] ) {
			return $rules;
		}

		if ( $process_line ) {
			$lines[$idx] = trim($lines[$idx]);
			$elements = preg_split("/\s+/", $lines[$idx]);
			# Each rule must contain exactly 3 elements:
			#    1) The pound symbol (a comment in the .htaccess file)
			#    2) A regex for input pattern matching
			#    3) A replacement pattern
			# '#!' marks a comment inside the CC Permalink Mapper block
			if ( count($elements) == 3 && ($elements[0] != "#!") ) {
				# the original pattern is a mod_rewrite-style pattern so
				# we need to actually esacpe the forward slashes so that
				# it's compatible with the pcre's of php.
				$rule['pattern'] = preg_replace("/\//", "\/", $elements[1]);
				$rule['replacement'] = $elements[2];
				$rules[] = $rule;
			}
		}
	}

	return false;

}

?>
