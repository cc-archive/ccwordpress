<?php
class SandBoxSandBoxController extends w11v_Controller_Action_AdminMenu {
	public function SandBoxAction($content) {
		$this->debug ( $this->application ()->Settings ()->name );
		$this->debug($this->application()->loader()->includepath());
		return $content;
	}
}