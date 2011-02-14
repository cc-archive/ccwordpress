<?php /*
Plugin Name: SurveyMe
Plugin URI: http://formplugins.dcoda.co.uk/2011/01/27/quick-fix-of-case/
Description: Easy to modify Survey Form
Author: dcoda
Author URI: http://dcoda.co.uk
Version: 1.7.1
 */ 
require_once  dirname ( __FILE__ ) . '/library/survey/s9v/application.php';

	@include_once (ABSPATH.'wp-admin/includes/post.php');
	
new s9v_application ( __FILE__,array() );
