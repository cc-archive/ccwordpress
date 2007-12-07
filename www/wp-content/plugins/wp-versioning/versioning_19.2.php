<?php
/*
Plugin Name: WP Versioning
Plugin URI: http://benjismith.net/index.php/2006/08/03/disheartening-wordpress-bug/
Description: Page & post versioning
Original Authors: Brian Groce ( http://briangroce.com )
Update Author: Benji Smith ( http://benjismith.net )
Version: 19.2
*/ 

//********************************************//
// versioning_admin
//********************************************//
function versioning_admin() {
	add_options_page(__("Versioning"), __('Versioning'), 8, 'wp-versioning/index.php');
}

function insert_version_record()  {
	
	global $wpdb, $table_prefix;
	$wpdb->version_ids = $table_prefix . 'version_ids';	
	$wpdb->versions = $table_prefix . 'versions';	
  
  $post_ID = $_POST['post_ID'];
  
  // Get the post date from the live version
	$get_date = "SELECT post_date, post_date_gmt FROM $wpdb->posts WHERE ID = '$post_ID'";
	$date_result = mysql_query($get_date);	  

	while ($date_row = mysql_fetch_assoc($date_result)) {	
	  $post_date = $date_row['post_date'];
		$post_date_gmt = $date_row['post_date_gmt'];	
		}
  
	$post_author = $_POST['post_author'];	
	$content = apply_filters('content_save_pre', $_POST['content']);		
  $post_title = $_POST['post_title'];
  $post_category = $_POST['post_category'];
  $post_excerpt = $_POST['post_excerpt'];
  $post_status = $_POST['post_status'];
  $comment_status = $_POST['comment_status'];
  $ping_status = $_POST['ping_status'];
  $post_password = $_POST['post_password'];
  $post_name = $_POST['post_name'];
  $to_ping = $_POST['to_ping'];
  $pinged = $_POST['pinged'];
  $post_modified = current_time('mysql');
  $post_modified_gmt = current_time('mysql', 1); 
  $post_content_filtered = $_POST['post_content_filtered'];

	$post_parent = 0;
	if (isset($_POST['parent_id'])) {
		$post_parent = $_POST['parent_id'];
	}

  $guid = $_POST['guid'];
  $menu_order = $_POST['menu_order']; 

	$select_version = "SELECT current_post_version FROM $wpdb->version_ids WHERE post_ID = '$post_ID' ";
	$version_result = mysql_query($select_version);
	$nrows = mysql_num_rows($version_result);

	while ($row = mysql_fetch_assoc($version_result)) {	
		$current_post_version = $row['current_post_version'];		
		$current_post_version++;
		}
  
	// Insert the initial record
	if ($nrows == "") {		
		$current_post_version = '1';		
		$insert_new = "INSERT INTO $wpdb->version_ids (post_ID, current_post_version) VALUES ('$post_ID', '$current_post_version')";		
		$result = mysql_query($insert_new);			
		}
	
	// Update the version
	else {		
		$update  = "UPDATE $wpdb->version_ids ";
		$update .= "SET ";
		$update .= "current_post_version = '$current_post_version' ";
		$update .= "WHERE post_ID = '$post_ID'";
		$result = mysql_query($update);				
		}

	// Versioning Entries
	$post_version = $current_post_version;		
	
  $sql = "INSERT INTO $wpdb->versions
  				(post_ID, post_version, post_author, post_date, post_date_gmt, post_content, 
          post_title, post_category, post_excerpt, post_status, comment_status, ping_status, 
          post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, 
          post_content_filtered, post_parent, guid, menu_order) 
          VALUES 
          ('$post_ID', '$post_version', '$post_author', '$post_date', '$post_date_gmt', 
          '$content', '$post_title', '$post_category', '$post_excerpt', '$post_status', 
          '$comment_status', '$ping_status', '$post_password', '$post_name', '$to_ping', 
          '$pinged', '$post_modified', '$post_modified_gmt', '$post_content_filtered', 
          '$post_parent', '$guid', '$menu_order')
          "; 
				
	mysql_query( $sql );	
	}

//********************************************//
// Actions
//********************************************//

// Admin menu
add_action('admin_menu', 'versioning_admin');

// Version Page
add_action('edit_post', 'insert_version_record', 5);
?>