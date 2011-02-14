<?php
class s9v_form_pollme extends s9v_form {
	protected function do_submit() {
		return parent::do_submit () . $this->render_poll ();
	}
	protected function answered() {
		return parent::answered () . $this->render_poll ();
	}
	protected function closed() {
		return parent::closed () . $this->render_poll ();
	}
	private function render_poll() {
		$page = "";
		$this->view->Settings = $this->s9v_forms->settings ();
		$this->view->url = $this->control_url ( '/PollMe/' . strtolower ( $this->view->Settings ['form'] ) . '.png' );
		$page .= $this->renderScript ( 'Results/HTML/Results.phtml' );
		return $page;
	}
	protected function submit() {
		$newpost = array ();
		foreach ( $_POST as $key => $value ) {
			if (is_array ( $value )) {
				$newpost [$key] = $value;
			} else {
				$newpost [$value] = 1;
			}
		}
		$_POST = $newpost;
		parent::submit ();
	}
}		