<?php
if (! class_exists ( 'b11v_Application' )) :
	require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'base/b11v/Application.php';
	class w11v_Application extends b11v_Application {
		protected function set_frontcontroller() {
			parent::set_frontcontroller ( w11v_Controller_Front::getInstance ( $this->application () ) );
		}
		protected $passed_classes = null;
		public function __construct($filename = "", $classes = array()) {
			$this->passed_classes = $classes;
			add_action ( "plugins_loaded", array ($this, "setup" ) );
			parent::__construct ( $filename );
			$this->info = new w11v_Info ( $this );
		}
		public function relative_path($uri = null) {
			global $current_blog;
			if (null === $uri) {
				$uri = $_SERVER ['REQUEST_URI'];
			}
			//$uri = substr ( $uri , strlen ( $current_blog->path ) );
			$uri = substr ( $uri, strlen ( get_option ( 'site_url' ) ) );
			
			$uri = explode ( '?', $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri, '/' );
			$uri = '/' . rtrim ( $uri, '/' );
			return $uri;
		}
		private static $templateDirBase = null;
		private static function templateDirBase() {
			if (is_null ( self::$templateDirBase )) {
				self::$templateDirBase = dirname ( dirname ( dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) ) );
			}
			return self::$templateDirBase;
		}
		private static $templateDir = null;
		public static function templateDir($subfolder = null) {
			if (! is_null ( $subfolder ) || is_null ( self::$templateDir )) {
				self::$templateDir = self::templateDirBase () . DIRECTORY_SEPARATOR . $subfolder;
			}
			return self::$templateDir;
		}
		public function setup() {
			load_plugin_textdomain ( get_class ( $this ), false, dirname ( plugin_basename ( $this->application ()->filename () ) ) . "/languages/" );
		}
		public function preload_classes($classes = array()) {
			$classes = ( array ) $classes;
			array_unshift ( $classes, 'w11v_Info', 'w11v_Values', 'w11v_Table','w11v_Table_SiteMeta', 'w11v_Table_Sites', 'w11v_Table_Site', 'w11v_Table_Posts', 'w11v_Table_Blogs', 'w11v_Table_Blog', 'w11v_Table_Options', 'w11v_Table_Users', 'w11v_Table_UserMeta', 'w11v_View', 'w11v_Controller_Action_Abstract', 'w11v_Controller_Action_Action', 'w11v_Controller_Action_AdminMenu', 'w11v_Controller_Action_Control', 'w11v_Controller_Action_Filter', 'w11v_Controller_Front', 'w11v_Controller_Dispatcher', 'w11v_Table_Comments' );
			foreach ( $this->passed_classes as $class ) {
				$classes [] = $class;
			}
			parent::preload_classes ( $classes );
		}
		private $info = null;
		public function info() {
			return $this->info;
		}
	}

endif;