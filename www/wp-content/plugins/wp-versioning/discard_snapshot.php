<?php

	$post_ID = mysql_escape_string($HTTP_GET_VARS['post_ID']);
	$post_version = mysql_escape_string($HTTP_GET_VARS['post_version']);

	$sql = "DELETE FROM $wpdb->versions WHERE";
	$sql .= " $wpdb->versions.post_version = $post_version ";   
	$sql .= " AND $wpdb->versions.post_ID = $post_ID "; 
	
	$result = mysql_query($sql);
	
	if (!$result) {
	   echo "Could not successfully run query ($sql) from DB: " . mysql_error();
	   exit;
	}

        # added by Nathan Kinkade.  if there are no more versions in the wp_versions table
        # after deleting, then we want to remove the record for versioning from the wp_version_ids
        # tables so that only pages with actually versioning show up in the list.  this will
        # be more intuitive.
        $sql = "
                SELECT * FROM $wpdb->versions
                WHERE $wpdb->versions.post_ID = '$post_ID'
        ";
        $result = mysql_query($sql);
        if ( ! mysql_num_rows($result) ) {
                $sql = "
                        DELETE FROM $wpdb->version_ids
                        WHERE post_ID = '$post_ID'
                ";
                mysql_query($sql);
        }
	
?>

<br />
<br />

<center>
Done Deleting snapshot!!<br/>
(POST ID: <?php echo $post_ID; ?>, VERSION: <?php echo $post_version; ?>) 
</center>

<br/>
<br/>
