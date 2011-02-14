<?php
if (! class_exists ( 'b11v_Application' )) :
	require dirname ( __FILE__ ) . '/Base.php';
	class b11v_Application extends b11v_Base {
		private $_page = null;
		public function page() {
			if (null === $this->_page) {
				$this->set_page ();
			}
			return $this->_page;
		}
		public function set_page($page = null) {
			if (null === $page) {
				$this->_page = $this->relative_path ();
			} else {
				$this->_page = '/' . ltrim ( rtrim ( $page, '/' ), '/' );
			}
		}
		public function relative_path($uri = null) {
			if (null === $uri) {
				$uri = $_SERVER ['REQUEST_URI'];
			}
			$uri = explode ( '?', $uri );
			$uri = $uri [0];
			$uri = rtrim ( $uri, '/' );
			$project = dirname ( $this->filename () );
			$root_uri = $uri;
			while ( strpos ( $project, $root_uri ) === false ) {
				$root_uri = substr ( $root_uri, 0, strrpos ( $root_uri, '/' ) );
			}
			$uri = '/' . ltrim ( rtrim ( substr ( $uri, strlen ( $root_uri ) ), '/' ), '/' );
			return $uri;
		}
		private $_filename = null;
		public function filename() {
			return $this->_filename;
		}
		public function __construct($filename = "") {
			if (! class_exists ( 'b11v_Loader' )) {
				require_once dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'b11v/Loader.php';
			}
			$this->_loader = new b11v_Loader ( $this );
			parent::__construct ( $this );
			$this->_filename = $filename;
			$this->preload_classes ();
		}
		private $settings = null;
		public function settings() {
			if (null == $this->settings) {
				$this->settings = new b11v_Settings ( $this );
			}
			return $this->settings;
		}
		private $_frontController;
		public function frontcontroller() {
			$this->set_frontcontroller ();
			return $this->_frontcontroller;
		}
		protected function set_frontcontroller($controller = null) {
			if (null === $controller) {
				$this->_frontcontroller = b11v_Controller_Front::getInstance ( $this->application () );
			} else {
				$this->_frontcontroller = $controller;
			}
		}
		private $_loader = null;
		public function loader() {
			return $this->_loader;
		}
		protected function preload_classes($classes = array()) {
			$classes = ( array ) $classes;
			$loader = $this->loader ();
			array_unshift ( $classes, 'b11v_Info', 'b11v_Controller_Action', 'b11v_Type_Abstract', 'b11v_Type_String', 'b11v_Type_Array', 'b11v_FS', 'b11v_View', 'b11v_Http', 'b11v_Tag', 'b11v_Data_Abstract', 'b11v_Data_INI', 'b11v_Data_CSV', 'b11v_Data_XML', 'b11v_FS', 'b11v_Http', 'b11v_Controller_Front', 'b11v_Controller_Dispatcher', 'b11v_Table', 'b11v_Controller_Action_Direct', 'b11v_Settings' );
			foreach ( $classes as $class ) {
				$loader->load_class ( $class );
			}
		}
	}


endif;