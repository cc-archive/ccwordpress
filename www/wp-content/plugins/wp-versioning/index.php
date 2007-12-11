	<h3>WordPress Versioning Plugin v19.2 Beta Admin Area</h3>

<?php
	
	global $wpdb, $table_prefix;
	
	$wpdb->version_ids = $table_prefix . 'version_ids';	
	$wpdb->versions = $table_prefix . 'versions';	
	
	//********************************************************//
	// Secure the admin area
	//********************************************************//
	include_once (dirname(dirname(dirname(dirname(__FILE__)))) . "/wp-config.php");
	get_currentuserinfo();
	
		if ($user_level < 8) {
		die ("Sorry, you must be logged in and at least a level 8 user to access admin setup options.");
		}

	  if ($action == 'roll_back'){ include ("block_roll_back.php"); }
	  elseif ($action == 'roll_back_select'){ include ("block_roll_back_select.php"); }
	  elseif ($action == 'discard_snapshot'){ include ("discard_snapshot.php"); }
	  else { include ("block_view_versions.php"); }
	    
?>	  
