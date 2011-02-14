<?php
class s9v_form_quizme extends s9v_form {
	protected function do_submit() {
		return parent::do_submit () . $this->render_quiz ();
	}
	private function render_quiz() {
		$page = "";
		$this->view->correct = 0;
		foreach ( $this->view->DataFields as $key => $field ) {
			if ($field ['Type'] == 'radio') {
				if ($this->view->DataFields [$key] ['Value'] == $this->view->DataFields [$key] ['Answer']) {
					$this->view->correct ++;
				}
			} else {
				unset ( $this->view->DataFields [$key] );
			}
		}
		$this->view->answers = count ( $this->view->DataFields );
		$page .= $this->renderScript ( 'Results/HTML/Results.phtml' );
		return $page;
	}
}
		