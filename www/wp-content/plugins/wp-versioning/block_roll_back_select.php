<?php

	$post_ID = mysql_escape_string($HTTP_GET_VARS['post_ID']);
	$post_version = mysql_escape_string($HTTP_GET_VARS['post_version']);

	$sql = "SELECT * FROM $wpdb->versions WHERE post_ID = $post_ID ORDER BY post_version DESC ";
	$result = mysql_query($sql);
	
	if (!$result) {
	   echo "Could not successfully run query ($sql) from DB: " . mysql_error();
	   exit;
	}
	
	if (mysql_num_rows($result) == 0) {
	   echo "No records found.";
	   exit;
	}
?>	
	
	<?php echo "$post_title"; ?>
	
	<table cellspacing="3" cellpadding="6" style="border:1px solid #dddddd;margin:15px;">
		<tr>
			<th style="text-align:center;border:1px solid #dddddd;">Version</th>
			<th style="text-align:center;border:1px solid #dddddd;">Title</th>
			<th style="text-align:center;border:1px solid #dddddd;">Content</th>
			<th style="text-align:center;border:1px solid #dddddd;">Snapshot DateTime</th>
			<th style="text-align:center;border:1px solid #dddddd;" colspan="2"></th>
		</tr>	
	
<?php	
	while ($row = mysql_fetch_assoc($result)) {	
	  $post_ID = $row['post_ID'];
	  $post_version = $row['post_version'];
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
?>
		
		<tr>
		  <td style="text-align:center;border:1px solid #dddddd;"><?php echo $post_version; ?></td>
		  <td style="text-align:center;border:1px solid #dddddd;"><?php echo $post_title; ?></td>
		  <td style="text-align:left;border:1px solid #dddddd;"><?php echo nl2br(htmlspecialchars($post_content)); ?></td>
		  <td style="text-align:center;border:1px solid #dddddd;"><?php echo $post_modified; ?></td>
		  <td style="text-align:center;border:1px solid #dddddd;"><a href ="admin.php?page=wp-versioning/index.php&action=roll_back&post_ID=<?php echo "$post_ID"; ?>&post_version=<?php echo "$post_version"; ?>">RollBack</td>
		  <td style="text-align:center;border:1px solid #dddddd;"><a href ="admin.php?page=wp-versioning/index.php&action=discard_snapshot&post_ID=<?php echo "$post_ID"; ?>&post_version=<?php echo "$post_version"; ?>">Discard Snapshot</td>

<?php	} mysql_free_result($result);  // Free the memory ?>
	
	</table>	