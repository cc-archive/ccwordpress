<?php
class w11v_Controller_Front extends b11v_Controller_Front {
	public static function getInstance($application) {
		$filename = $application->filename ();
		if (! array_key_exists ( $filename, self::$_instance )) {
			self::$_instance [$filename] = new self ( $application );
			self::$_instance [$filename]->setup ();
		}
		return self::$_instance [$filename];
	}
	protected function setDispatcher() {
		parent::setDispatcher ( new w11v_Controller_Dispatcher ( $this->application () ) );
	}
}