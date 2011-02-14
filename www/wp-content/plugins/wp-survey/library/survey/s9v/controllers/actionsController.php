<?php
class s9v_controllers_actionsController extends w11v_Controller_Action_Action {
	public function wp_headAction() {
		$this->view->url = $this->url ( 'public/survey-style.php' );
		$this->view->url .= '?t=' . $this->application ()->Settings ()->slug;
		$this->view->_e ( $this->renderScript ( 'Common/head.phtml' ) );
		parent::wp_headAction ();
	}
	protected $s9v_forms = null;
	public function __construct($application) {
		parent::__construct ( $application );
		$this->s9v_forms = new s9v_forms ( $application );
		$this->set_template_folders ( array ($this->s9v_forms->type (), 'Default' ) );
	}
	public function initAction() {
		$settings = $this->s9v_forms->settings ();
		if ($settings ['action'] || $settings ['widget']) {
			add_action ( strtolower ( $this->s9v_forms->type () ), array ($this, 'render_marker' ) );
			if ($settings ['widget']) {
				$this->application ()->loader ()->load_class ( strtolower ( $this->s9v_forms->type () ).'Widget' );
				register_widget (strtolower ( $this->s9v_forms->type () ).'Widget' );
			}
		}
	}
	public function render_marker() {
		$class = strtolower ( 's9v_form_'.$this->s9v_forms->type () );
		$form = new $class ( $this->application () );
		echo $form->render_marker ( array () );
	}
}