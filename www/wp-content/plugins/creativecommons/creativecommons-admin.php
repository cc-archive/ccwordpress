<?php
/* 

 Admin pages for CC plugin

*/


/* Admin Pages */

// Hook for adding admin menus
add_action('admin_menu', 'cc_plugin_add_pages');

// action function for above hook
function cc_plugin_add_pages() {
    // Add a new submenu under Manage:
    add_options_page('CC Settings', 'CC Settings', 'manage_options', __FILE__, 'cc_manage_options');
}

function cc_manage_options() {
  echo "<h2>CC Options</h2>";
}

?>

