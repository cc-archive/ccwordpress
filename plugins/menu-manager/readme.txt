Installation:
==============================================================================
1. Upload the menu-manager directory into your wp-content/plugins directory
2. Activate it in the Plugin options
3. Go to Manage -> Menu Manager to create site navigation.

Example Usage:
==============================================================================
1. For list based navigation
   echo wpfm_create("menu-name"); 
2. For DropDown list
   echo wpfm_create("menu-name",true,'select','--Quick Links--');

To do
==============================================================================
1. Hierarchical and Non-Hierarchical Option to work