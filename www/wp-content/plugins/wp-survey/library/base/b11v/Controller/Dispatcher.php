<?php
class b11v_Controller_Dispatcher extends b11v_Base {
	protected $_controllers = null;
	public function controllers() {
		$this->_controllers = array ();
		$paths = $this->application ()->frontcontroller ()->getControllerPaths ();
		$dirs = $this->application ()->loader ()->includepath ( $paths );
		foreach ( $dirs as $dir ) {
			$fs = new b11v_FS ( $this->application (), $dir );
			$fs_controllers = $fs->dir ( '*Controller.php' );
			foreach ( $fs_controllers as $fs_controller ) {
				$this->_controllers [] = $fs_controller;
			}
		}
		return $this->_controllers;
	}
}