<?php
if (! class_exists ( 'b11v_Base' )) :
	abstract class b11v_Base {
		protected static $_instance = null;
		public function __construct($application = null) {
			$this->_application = $application;
		}
		private $_application = null;
		public function set_application($application = null) {
			$this->_application = $application;
		}
		public function application() {
			if (null === $this->_application) {
				throw new Exception ( "Application not set \n" );
			}
			return $this->_application;
		}
		public function debug() {
			$this->show ( func_get_args (), false );
		}
		public function tdebug() {
			$this->show ( func_get_args (), true );
		}
		public function dodebug() {
			return (getenv ( 'DEBUG' ) == 'yes');
		}
		private function show($values, $type) {
			if (! $this->dodebug ()) {
				return;
			}
			echo "<pre>";
			foreach ( $values as $value ) {
				if ($type) {
					var_dump ( $value );
				} else {
					print_r ( $value );
				}
			}
			echo "</pre><br/>";
		}
	}



endif;