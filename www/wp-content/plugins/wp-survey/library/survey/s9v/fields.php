<?php
class s9v_fields extends w11v_Controller_Action_Action {
	public function answered($fields) {
		$return = false;
		$fields = $this->HTML_DataFields ( $fields );
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$return = true;
			foreach ( $fields as $field ) {
				$return = ! $field ['error'];
				if (! $return) {
					break;
				}
			}
		}
		return $return;
	}
	public function HTML_DataFields($DataFields) {
		$return = array ();
		foreach ( $DataFields as $field ) :
			$this->field = new s9v_field ( $this->application (), $this->settings );
			$return [] = $this->field->settings ( $field );
		endforeach;
		return $return;
	}
	protected $settings = null;
	public function __construct($application, $key, $settings) {
		parent::__construct ( $application );
		$this->set_template_folders ( array ($key, 'Default' ) );
		$this->settings = $settings;
	}
	public function html($fields) {
		$return = '';
		$fields = $this->HTML_DataFields ( $fields );
		foreach ( $fields as $field ) {
			$return .= $field ['HTML'];
		}
		return $return;
	}
}