<?php
abstract class b11v_Type_Abstract extends b11v_Base {
	// has to be abstract as the function is static the __CLASS__ always return the parent class
	abstract public static function is($object);
	protected $value = null;
	public function value($value = null) {
		if (! is_null ( $value )) {
			$this->value = $value;
		}
		return $this->value;
	}
	public function __construct($value = null) {
		parent::__construct ();
		$this->value ( $value );
	}
}