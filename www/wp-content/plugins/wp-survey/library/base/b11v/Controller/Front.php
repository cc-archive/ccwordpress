<?php
class b11v_Controller_Front extends b11v_Base {
	public function getControllerPaths() {
		return $this->controllerPaths;
	}
	public function getViewPaths() {
		return $this->viewPaths;
	}
	protected $_dispatcher = null;
	public function getDispatcher() {
		$this->setDispatcher ();
		return $this->_dispatcher;
	}
	protected static $_instance = array ();
	public static function getInstance($application) {
		$filename = $application->filename ();
		if (! array_key_exists ( $filename, self::$_instance )) {
			self::$_instance [$filename] = new self ( $application );
			self::$_instance [$filename]->setup ();
		}
		return self::$_instance [$filename];
	}
	protected function setDispatcher($dispatcher = null) {
		if (null === $this->_dispatcher) {
			if (null === $dispatcher) {
				$this->_dispatcher = new b11v_Controller_Dispatcher ( $this->application () );
			} else {
				$this->_dispatcher = $dispatcher;
			}
		}
	}
	public function setup() {
		$this->controllerPaths = array('Controllers/');
		$this->viewPaths = array('Views/');
		$this->setDispatcher ();
	}
}