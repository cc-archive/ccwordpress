<?php
/* 

 Admin pages for CC plugin

*/

$cc_db_version = "1";
$cc_db_rss_table = $wpdb->prefix . "cc_rss_feeds";

/* On first activation create rss feeds table, insert International Planet CC */
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
    $feed_url = "http://planet.creativecommons.org/jurisdictions/rss20.xml";
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

/* Options -> CC Settings page in WP admin */
function cc_manage_options() {
  global $post_msg;
    global $wpdb;
  global $cc_db_rss_table;
  
  if ($_REQUEST['submit_new']) {
    cc_admin_new_feed();
  }
  
  if ($_REQUEST['submit_update']) {
    cc_admin_update_feed();
  }
  
  if ($_REQUEST['submit_delete']) {
    cc_admin_delete_feed();
  }
  
  $action = "Add";
  $submit_action = "new";
  if ($_REQUEST['submit_edit']) {
    $action = "Update";
    $submit_action = "update";
    
    $feed = $wpdb->get_row("SELECT * FROM $cc_db_rss_table WHERE id=" . $wpdb->escape($_REQUEST['feed_id']) . ";");
    $feed_id = $feed->id;
    $feed_name = $feed->name;
    $feed_url = $feed->url;
  }

  $feedlist = cc_admin_get_feeds();

echo <<< END_OF_ADMIN
<div class="wrap">
  <div id="statusmsg">${post_msg}</div>
  <h2>CC Settings</h2>
  <h3>RSS Importer</h3>
  <p><strong>Current feeds</strong></p>
  <table>
  ${feedlist}
  </table>
  <h4>${action} Feed</h4>
    <form method="post">
    <input type="hidden" name="feed_id" value="${feed_id}"/>
    <label for="feed_name"><strong>Name:</strong></label>
    <input type="text" name="feed_name" size="30" value="${feed_name}" /><br/>
    <label for="feed_url"><strong>URL:</strong></label>
    <input type="text" name="feed_url" size="30" value="${feed_url}" />
    <input type="submit" name="submit_${submit_action}" value="${action}" />
    </form>
  <p>
   
  </p>
</div>

END_OF_ADMIN;
}

function cc_admin_get_feeds() {
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
    $feedlist .= "<td><form method=\"post\"><input type=\"hidden\" name=\"feed_id\" value=\"{$feed->id}\"/> <input type=\"submit\" name=\"submit_edit\" value=\"Edit\" /></form></td>";
    $feedlist .= "<td><form method=\"post\"><input type=\"hidden\" name=\"feed_id\" value=\"{$feed->id}\"/> <input type=\"submit\" name=\"submit_delete\" value=\"Delete\" /></form></td>";
    $feedlist .= "</tr>";
  }
  $feedlist .= "</table>";
  
  return $feedlist;
}

function cc_admin_new_feed() {
  global $wpdb;
  global $cc_db_rss_table;

  $insert = "INSERT INTO " . $cc_db_rss_table . " (name, url) " .
            "VALUES ('" . $wpdb->escape($_REQUEST['feed_name']) . "','" . $wpdb->escape($_REQUEST['feed_url']) . "')";
  $results = $wpdb->query( $insert );
  
  
}

function cc_admin_update_feed() {
  global $wpdb;
  global $cc_db_rss_table;

  $insert = "UPDATE " . $cc_db_rss_table .
            " SET name = '" . $wpdb->escape($_REQUEST['feed_name']) . "', url = '" . $wpdb->escape($_REQUEST['feed_url']) . "' " . 
            "WHERE id=" . $wpdb->escape($_REQUEST['feed_id']) . ";";
  $results = $wpdb->query( $insert );
  
  
}

function cc_admin_delete_feed() {
  global $wpdb;
  global $cc_db_rss_table;
  
  $q = "DELETE FROM " . $cc_db_rss_table . " WHERE id=" . $wpdb->escape($_REQUEST['feed_id']) . ";";
  $results = $wpdb->query( $q );

}




?>
