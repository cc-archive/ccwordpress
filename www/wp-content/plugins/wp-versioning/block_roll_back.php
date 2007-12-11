<?php

		$post_ID = mysql_escape_string($HTTP_GET_VARS['post_ID']);
		$post_version = mysql_escape_string($HTTP_GET_VARS['post_version']);

		// Set Variables
		$now = current_time('mysql');
		$now_gmt = current_time('mysql', 1); 

		$sql = "SELECT ";
		$sql .= " post_version = $wpdb->versions.post_version, ";   
		$sql .= " current_post_version = $wpdb->version_ids.current_post_version, "; 
		$sql .= " $wpdb->versions.post_ID, "; 
		$sql .= " post_author , ";
		$sql .= " post_date , ";
		$sql .= " post_date_gmt , ";
		$sql .= " post_content , ";
		$sql .= " post_title , ";
		$sql .= " post_category , ";
		$sql .= " post_excerpt , ";
		$sql .= " post_status , ";
		$sql .= " comment_status , ";
		$sql .= " ping_status , ";
		$sql .= " post_password , ";
		$sql .= " post_name , ";
		$sql .= " to_ping , ";
		$sql .= " pinged , ";
		$sql .= " post_modified , ";
		$sql .= " post_modified_gmt , ";
		$sql .= " post_content_filtered , ";
		$sql .= " post_parent , ";
		$sql .= " guid , ";
		$sql .= " menu_order  ";
		$sql .= "FROM $wpdb->versions, $wpdb->version_ids WHERE $wpdb->versions.post_ID = '$post_ID' ";
		$sql .= "AND $wpdb->versions.post_version = '$post_version' ";
		$sql .= "AND $wpdb->versions.post_ID = $wpdb->version_ids.post_ID ";	
	
	$result = mysql_query($sql);
	
	if (!$result) {
	   echo "Could not successfully run query ($sql) from DB: " . mysql_error();
	   exit;
	}
	
	if (mysql_num_rows($result) == 0) {
	   echo "No records found.";
	   exit;
	}

	while ($row = mysql_fetch_assoc($result)) {	
		$post_version = $row['post_version'];		
		$current_post_version = $row['current_post_version'];				
	  $post_ID = $row['post_ID'];
		$post_author = $row['post_author'];
		$post_date = $row['post_date'];
		$post_date_gmt = $row['post_date_gmt'];
	  $post_content = $row['post_content'];
	  $post_title = $row['post_title'];
	  $post_category = $row['post_category'];
	  $post_excerpt = $row['post_excerpt'];
	  $post_status = $row['post_status'];
	  $comment_status = $row['comment_status'];
	  $ping_status = $row['ping_status'];
	  $post_password = $row['post_password'];
	  $post_name = $row['post_name'];
	  $to_ping = $row['to_ping'];
	  $pinged = $row['pinged'];
	  $post_modified = $row['post_modified'];
	  $post_modified_gmt = $row['post_modified_gmt'];
	  $post_content_filtered = $row['post_content_filtered'];
	  $post_parent = $row['post_parent'];
	  $guid = $row['guid'];
	  $menu_order = $row['menu_order'];				
		}
	
	$sql2 = "SELECT * FROM $wpdb->version_ids WHERE post_ID = '$post_ID' ";
	$result2 = mysql_query($sql2);
	
		while ($row2 = mysql_fetch_assoc($result2)) {
			$current_post_version = $row2['current_post_version'];
		}

// UPDATE LIVE VERSION

	$post_author = addslashes( $post_author );
	$post_content = addslashes( $post_content );
	$post_title = addslashes( $post_title );
	$post_excerpt = addslashes( $post_excerpt );

	$update_live  = "UPDATE $wpdb->posts ";
	$update_live .= "SET ";
	$update_live .= "post_author = '$post_author', ";
	$update_live .= "post_date = '$post_date', ";
	$update_live .= "post_date_gmt = '$post_date_gmt', ";
	$update_live .= "post_content = '$post_content', ";
	$update_live .= "post_title = '$post_title', ";
	$update_live .= "post_category = '$post_category', ";
	$update_live .= "post_excerpt = '$post_excerpt', ";
	$update_live .= "post_status = '$post_status', ";
	$update_live .= "comment_status = '$comment_status', ";
	$update_live .= "ping_status = '$ping_status', ";
	$update_live .= "post_password = '$post_password', ";
	$update_live .= "post_name = '$post_name', ";
	$update_live .= "to_ping = '$to_ping', ";
	$update_live .= "pinged = '$pinged', ";
	$update_live .= "post_modified = '$post_modified', ";
	$update_live .= "post_modified_gmt = '$post_modified_gmt', ";
	$update_live .= "post_content_filtered = '$post_content_filtered', ";
	$update_live .= "post_parent = '$post_parent', ";
	$update_live .= "guid = '$guid', ";
	$update_live .= "menu_order = '$menu_order' ";
	$update_live .= "WHERE ID = '$post_ID'";		
	
	$result = mysql_query($update_live);	

// INSERT NEW VERSION

	$new_post_version = $current_post_version;
	$new_post_version++;

  $insert_version  = "INSERT INTO $wpdb->versions ( ";
  $insert_version .= "post_ID, ";
  $insert_version .= "post_version, ";
  $insert_version .= "post_author, ";
  $insert_version .= "post_date, ";
  $insert_version .= "post_date_gmt, ";
  $insert_version .= "post_content, ";
  $insert_version .= "post_title, ";
  $insert_version .= "post_category, "; 
  $insert_version .= "post_excerpt, ";
  $insert_version .= "post_status, ";
  $insert_version .= "comment_status, "; 
  $insert_version .= "ping_status, ";
  $insert_version .= "post_password, ";
  $insert_version .= "post_name, ";
  $insert_version .= "to_ping, ";
  $insert_version .= "pinged, ";
  $insert_version .= "post_modified, ";
  $insert_version .= "post_modified_gmt, ";
  $insert_version .= "post_content_filtered, ";
  $insert_version .= "post_parent, ";
  $insert_version .= "guid, ";
  $insert_version .= "menu_order) ";
  $insert_version .= "VALUES ( ";
  $insert_version .= "$post_ID, "; 
  $insert_version .= "$new_post_version, ";
  $insert_version .= "$post_author, ";
  $insert_version .= "'$now', ";
  $insert_version .= "'$now_gmt', ";
	$insert_version .= "'$post_content', ";
	$insert_version .= "'$post_title', ";
	$insert_version .= "'$post_category', ";
	$insert_version .= "'$post_excerpt', ";
	$insert_version .= "'$post_status', ";
  $insert_version .= "'$comment_status', ";
  $insert_version .= "'$ping_status', ";
  $insert_version .= "'$post_password', ";
  $insert_version .= "'$post_name', ";
  $insert_version .= "'$to_ping', ";
	$insert_version .= "'$pinged', ";
	$insert_version .= "'$now', ";
	$insert_version .= "'$now_gmt', ";
	$insert_version .= "'$post_content_filtered', ";
  $insert_version .= "'$post_parent', ";
  $insert_version .= "'$guid', ";
  $insert_version .= "'$menu_order')";
				
	$result_insert_version = mysql_query($insert_version);

// UPDATE VERSION NUMBER

	$update_version  = "UPDATE $wpdb->version_ids ";
	$update_version .= "SET ";
	$update_version .= "current_post_version = $new_post_version ";
	$update_version .= "WHERE post_ID = $post_ID ";
	
	$result_update_version = mysql_query($update_version);	

?>

<br />
<br />

<center>Complete.</center>

<br />
<br />
