<?php

	$sql = "SELECT * FROM $wpdb->version_ids left join $wpdb->posts on $wpdb->posts.ID = $wpdb->version_ids.post_ID ";
	$result = mysql_query($sql);
	
	if (!$result) {
	   echo "Could not successfully run query<br /><br />";
	   echo "If you're getting this error and your database server is up, ";
	   echo "you may need to run the <a href='admin.php?page=wp-versioning/install.php'>install</a> or update your connection information.<br />";
	   exit;
	}
	
	if (mysql_num_rows($result) == 0) {
	   echo "No records found.";
	   exit;
	}
?>

	<table width="100%" cellspacing="3" cellpadding="3" style="border:1px solid #dddddd;margin:15px;">
		<tr>
			<th style="text-align:center;border:1px solid #dddddd;">Post Title</th>
			<th style="text-align:center;border:1px solid #dddddd;">Published Version</th>
			<th style="text-align:center;border:1px solid #dddddd;"></th>
		</tr>
	
<?php	
	while ($row = mysql_fetch_assoc($result)) {	
	  $post_ID = $row['post_ID'];
		$current_post_version = $row['current_post_version'];	
		$post_title = $row['post_title'];
?>		
	
	<tr>
		<td style="text-align:center;border:1px solid #dddddd;"><?php echo "$post_title"; ?></td>
		<td style="text-align:center;border:1px solid #dddddd;"><?php echo "$current_post_version"; ?></td>
		<td style="text-align:center;border:1px solid #dddddd;"><a href="admin.php?page=wp-versioning/index.php&action=roll_back_select&post_ID=<?php echo "$post_ID"; ?>">Choose Rollback Snapshot</a></td>
	</tr>
		
<?php		
		} 		
		mysql_free_result($result);  // Free the memory 	
?>

	</table>