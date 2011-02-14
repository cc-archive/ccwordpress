<?php
class s9v_controllers_filtersController extends w11v_Controller_Action_Filter {
	protected $s9v_forms = null;
	public function __construct($application) {
		parent::__construct ( $application );
		$this->s9v_forms = new s9v_forms ( $application );
		$this->set_template_folders ( array ($this->s9v_forms->type (), 'Default' ) );
	}
	public function the_contentAction($content) {
		$content = $this->marker ( $this->s9v_forms->type (), $content );
		return $content;
	}
	public function render_marker($match) {
		$form = new s9v_form ( $this->application () );
		return $form->render_marker ( $match );
	}
	public function QuizMe_Marker($match) {
		$form = new s9v_form_quizme ( $this->application () );
		return $form->render_marker ( $match );
	}
	public function PollMe_Marker($match) {
		$form = new s9v_form_pollme ( $this->application () );
		return $form->render_marker ( $match );
	}
	public function ContactMe_Marker($match) {
		$form = new s9v_form_contactme ( $this->application () );
		return $form->render_marker ( $match );
	}
	public function SurveyMe_Marker($match) {
		$form = new s9v_form_survey ( $this->application () );
		return $form->render_marker ( $match );
	}
	public function Submission_Marker($match) {
		$form = new s9v_form_submission ( $this->application () );
		return $form->render_marker ( $match );
	}
}
		