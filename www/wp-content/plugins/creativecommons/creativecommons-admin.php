<?php
/* 

 Admin pages for CC plugin

*/

$cc_db_version = "1";
$cc_db_rss_table = $wpdb->prefix . "cc_rss_feeds";


function cc_plugin_activate () {
  global $wpdb;
  global $cc_db_version;
  global $cc_db_rss_table;
  
  if($wpdb->get_var("show tables like '" . $cc_db_rss_table . "'") != $cc_db_rss_table) {
    $sql = "CREATE TABLE " . $cc_db_rss_table . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              name varchar(255) NOT NULL,
              url varchar(255) NOT NULL, 
              UNIQUE KEY (id)     
            );";
    
    require_once(ABSPATH . 'wp-admin/upgrade.php');
    dbDelta($sql);  
    
    $feed_name = "Planet CC";
    $feed_url = "http://planet.creativecommons.org/affiliates/rss20.xml";
    $insert = "INSERT INTO " . $cc_db_rss_table . " (name, url) " .
            "VALUES ('" . $wpdb->escape($feed_name) . "','" . $wpdb->escape($feed_url) . "')";
    $results = $wpdb->query( $insert );
    
    add_option("cc_db_version", $cc_db_version);
  }
  
}

/* Admin Pages */

// Hook for adding admin menus
add_action('admin_menu', 'cc_plugin_add_pages');

// action function for above hook
function cc_plugin_add_pages() {
    // Add a new submenu under Manage:
    add_options_page('CC Settings', 'CC Settings', 'manage_options', __FILE__, 'cc_manage_options');
}

function cc_manage_options() {
  global $post_msg;
  global $wpdb;
  global $cc_db_rss_table;

  $feeds = $wpdb->get_results("SELECT * FROM $cc_db_rss_table;");

  $feedlist = "<table>";
  $feedlist .= "<tr><th>ID</th><th>Name</th><th>URL</th></tr>";
  foreach ($feeds as $feed) {
    $feedlist .= "<tr>";
    $feedlist .= "<td>{$feed->id}</td>";
    $feedlist .= "<td>{$feed->name}</td>";
    $feedlist .= "<td>{$feed->url}</td>";
    $feedlist .= "</tr>";
  }
  $feedlist .= "</table>";

echo <<< END_OF_ADMIN
<div class="wrap">
  <div id="statusmsg">${post_msg}</div>
  <h2>CC Settings</h2>
  <h3>RSS Importer</h3>
  <p><strong>Current feeds</strong></p>
  <table>
  ${feedlist}
  </table>
  <p>Add and delete RSS feeds</p>
</div>

END_OF_ADMIN;
}

?>
