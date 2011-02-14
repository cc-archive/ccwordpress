<?php
class s9v_forms extends w11v_Controller_Action_Action {
	protected $forms=null;
	public function forms() {
		if(is_null($this->forms))
		{
			$sql = "SELECT `option_name`%s WHERE option_name LIKE  '%s_%%' AND option_value != '%s';";
			$from = $this->options->name();
			$this->options->from($from);
			$type = strtolower ( $this->type () );
			$sql = sprintf($sql,$from,$type,$type);
			$results = $this->options->execute ( $sql);
			foreach ( $results as $key => $value ) {
				$results [$key] = substr ( $value ['option_name'], strlen ( $this->type () ) + 1 );
			}
			if (! in_array ( 'default', $results )) {
				$results [] = 'default';
			}
			sort ( $results );
			$this->forms=array();
			foreach ( $results as $key => $value ) {
				$this->forms[$value]=$value;
			}
		
		}
		return $this->forms;
	}
	private $subkey = null;
	private $key = null;
	public function type() {
		return $this->key;
	}
	public function set_form($name) {
		$this->subkey = $name;
		$this->options = new w11v_Table_Options ( array ($this->key, $this->subkey ) );
	}
	public function form() {
		return $this->subkey;
	}
	public function set_attributes($match) {
		if (isset ( $match ['attributes'] )) {
			$this->attributes = $match ['attributes'];
		}
	}
	public function get_type($type = 'radio') {
		$return = array ();
		foreach ( $this->DataFields () as $value ) {
			if ($value ['Type'] == $type) {
				$return [] = $value;
				break;
			}
		}
		return $return;
	}
	private $attributes = array ();
	public function settings() {
		$return = array ();
		$this->set_template_folders ( array ('Default' ) );
		$default = $this->renderScript ( 'Settings.ini' );
		$this->set_template_folders ( array ($this->key, 'Default' ) );
		$plugin = $this->renderScript ( 'Settings.ini' );
		$options = $this->get ();
		if (is_array ( $default )) {
			$return = $default + $return;
		}
		if (is_array ( $plugin )) {
			$return = $plugin + $return;
		}
		if (is_array ( $options )) {
			$return = $options + $return;
		}
		if (is_array ( $this->attributes )) {
			$return = $this->attributes + $return;
		}
		if (isset ( $return ['fields'] ))
		{
			foreach ( $return ['fields'] as $key => $value ) {
				$return ['fields'] [$key] ['Values'] = str_replace ( ',', "\n", $value ['Values'] );
			}
		}
		if (isset ( $return ['colors'] )) {
			$return ['colors'] = str_replace ( ',', "\n", $return ['colors'] );
		}
		if(!isset($return['responses']))
		{
			$return['responses']=array();
		}
		$override_response = explode(',',$return['override_response']);
		foreach($override_response as $or)
		{
			if(!isset($return['responses'][$or]))
			{
				$return['responses'][$or]=null;
			}
		}
		foreach($return['responses'] as $key=>$value)
		{
			if(!in_array($key,$override_response))
			{
				unset($return['responses'][$key]);
			}
			else
			{
				if(is_null($value))
				{
					$return['responses'][$key] = $this->loadScript ($key);
				}
			}
		}
		return $return;
	}
	public function DataFields() {
		$field = new s9v_field($this->application(),$this->settings());
		$return = array ();
		$this->set_template_folders ( array ($this->key, 'Default' ) );
		$options = $this->get ();
		if (! isset ( $options ['fields'] )) {
			$return = $this->renderScript ( 'Collect/DataFields.csv' )->getArray ();
		} else {
			$return = $options ['fields'];
		}
		foreach ( ( array ) ($return) as $key => $value ) {
			$return [$key] ['Values'] = str_replace ( '\n', "\n", $return [$key] ['Values'] );
		}
		$user = null;
		foreach ( ( array ) ($return) as $key => $value ) {
			if ($value ['Type'] == 'name') {
				$user = $value;
				unset ( $return [$key] );
			}
		}
		if (null === $user) {
			$user = $field->new_field('name');
		}
		$email = null;
		foreach ( ( array ) ($return) as $key => $value ) {
			if ($value ['Type'] == 'email') {
				$email = $value;
				unset ( $return [$key] );
			}
		}
		if (null === $email) {
			$email = $field->new_field('email');
		}
		$submit = null;
		foreach ( ( array ) $return as $key => $value ) {
			if ($value ['Type'] == 'submit') {
				$submit = $value;
				unset ( $return [$key] );
			}
		}
		if (null === $submit) {
			$submit = $field->new_field('submit');
		}
		$cc = null;
		foreach ( ( array ) ($return) as $key => $value ) {
			if ($value ['Type'] == 'cc') {
				$cc = $value;
				unset ( $return [$key] );
			}
		}
		if (null !== $cc) {
			$return [] = $cc;
		}
		$captcha = null;
		foreach ( ( array ) ($return) as $key => $value ) {
			if ($value ['Type'] == 'number captcha') {
				$captcha = $value;
				unset ( $return [$key] );
			}
		}
		if (null !== $captcha) {
			$captcha ['Mandatory'] = 'yes';
			$return [] = $captcha;
		}
		$recaptcha = null;
		foreach ( ( array ) ($return) as $key => $value ) {
			if ($value ['Type'] == 'recaptcha') {
				$recaptcha = $value;
				unset ( $return [$key] );
			}
		}
		if (null !== $recaptcha) {
			$recaptcha ['Mandatory'] = 'yes';
			$return [] = $recaptcha;
		}
		$settings = $this->settings ();
		if ($settings ['force_userdetails']) {
			array_unshift ( $return, $user, $email );
		}
		$return [] = $submit;
		foreach($return as $key=>$value)
		{
			$return[$key]=$field->settings($value);
		}
		return $return;
	}
	private function key() {
		return strtolower ( $this->key ) . '_' . $this->subkey;
	}
	public function post() {
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['colors']))
			{
				$_POST ['colors'] = str_replace ( "\r", '', $_POST ['colors'] );
			}
			foreach ( $_POST ['fields'] as $key => $value ) {
				if (isset ( $value ['delete'] ) || $value ['Type'] == '') {
					unset ( $_POST ['fields'] [$key] );
				} else {
					$_POST ['fields'] [$key] ['Values'] = str_replace ( "\r", '', $value ['Values'] );
				}
			}
			usort ( $_POST ['fields'], array ($this, 'field_sort' ) );
			$status = $this->application ()->settings ()->survey;
			$_POST ['version'] = $status ['version'];
			$this->options->set ( $_POST );
		}
		return $this->get ();
	}
	public function get() {
		$options = $this->options->get ();
		if (isset ( $options ['fields'] ))
		{
			foreach ( $options ['fields'] as $key => $value ) {
				$options ['fields'] [$key] ['Values'] = str_replace ( ',', "\n", $value ['Values'] );
			}
		}
		if (isset ( $options ['colors'] )) {
			$options ['colors'] = str_replace ( ',', "\n", $options ['colors'] );
		}
		return $options;
	}
	public function field_sort($a, $b) {
		if ($a ['Position'] == $b ['Position']) {
			if ($a ['Name'] == $b ['Name']) {
				return 0;
			}
			return ($a ['Name'] < $b ['Name']) ? - 1 : 1;
		}
		return ($a ['Position'] < $b ['Position']) ? - 1 : 1;
	}
	public function copy($dest, $src = 'default') {
		if(is_array($src))
		{
			$data=$src;
		}
		else
		{
			$old = $this->subkey;
			$this->subkey = $src;
			$data = $this->settings ();
			$data ['fields'] = $this->DataFields ();
			$this->subkey = $old;
		}
		$this->options->set ( $data, array ($this->key, $dest ) );
	}
	public function delete($forms) {
		foreach ( ( array ) $forms as $form => $value ) {
			$this->options->delete ( array ($this->key, $form ) );
		}
	}
	private $options = null;
	public function __construct($application, $subkey= 'Default') {
		$this->key = $application->settings ()->name;;
		$this->set_form($subkey);
		parent::__construct ( $application );
	}
}