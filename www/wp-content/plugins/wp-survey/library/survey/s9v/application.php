<?php
if (! class_exists ( 's9v_application' )) :
	require_once dirname ( dirname ( dirname ( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'wordpress/w11v/Application.php';
	class s9v_application extends w11v_Application {
		public function preload_classes($classes = array()) {
			$classes = ( array ) $classes;
			array_unshift ( $classes, 's9v_forms', 's9v_fields', 's9v_recaptcha', 's9v_akismet', 's9v_controllers_settingsController', 's9v_controllers_filtersController', 's9v_controllers_controllerController', 's9v_table', 's9v_controllers_actionsController', 's9v_form', 's9v_field', 'b11v_Mail', 'w11v_Mail', 's9v_form_survey', 's9v_form_submission', 's9v_form_contactme', 's9v_form_quizme', 's9v_form_pollme' );
			foreach ( $this->passed_classes as $class ) {
				$classes [] = $class;
			}
			parent::preload_classes ( $classes );
		}
		public function __construct($filename = "", $classes = array()) {
			$this->passed_classes = $classes;
			parent::__construct ( $filename, $classes );
			add_action ( "admin_menu", array ($this, "pages" ) );
			$obj = new s9v_controllers_actionsController ( $this );
			$obj->setup ();
			$obj = new s9v_controllers_controllerController ( $this );
			$obj->setup ();
			$obj = new s9v_controllers_filtersController ( $this );
			$obj->setup ();
		}
		public function pages()
		{
			$obj = new s9v_controllers_settingsController ( $this );
			$obj->setup ();
		}
	}


endif;