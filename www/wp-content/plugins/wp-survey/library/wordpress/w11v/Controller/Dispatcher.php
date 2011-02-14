<?php
class w11v_Controller_Dispatcher extends b11v_Controller_Dispatcher {
	public function __construct($application) {
		parent::__construct ( $application );
		$this->setup ( true );
		add_action ( 'admin_menu', array ($this, 'setup' ) );
	}
	public function setup($notmenu = false) {
		$paths = $this->application ()->frontcontroller ()->getControllerPaths ();
		$dirs = $this->application ()->loader ()->includepath ( $paths );
		foreach ( $this->controllers () as $controller ) {
			$class = basename ( $controller, ".php" );
			$this->application ()->loader ()->load_class ( $class, $dirs );
			$controllerClass = new $class ( $this->application () );
			if ($notmenu) {
				switch ($controllerClass->getType ()) {
					case w11v_Controller_Action_Abstract::WP_FILTER :
					case w11v_Controller_Action_Abstract::WP_ACTION :
					case w11v_Controller_Action_Abstract::WP_CONTROL :
						$controllerClass->setup ();
				}
			} else {
				if ($controllerClass->getType () == w11v_Controller_Action_Abstract::WP_DASHBOARD) {
					$controllerClass->setup ();
				}
			}
		}
	}
}