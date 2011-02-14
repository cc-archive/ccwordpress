<?php
class s9v_form extends w11v_Controller_Action_Action {
	protected $s9v_forms = null;
	protected $s9v_akismet = null;
	protected $fields = null;
	public function __construct($application) {
		parent::__construct ( $application );
		$this->s9v_forms = new s9v_forms ( $application );
		$this->s9v_akismet = new s9v_akismet ( $application );
		$this->set_template_folders ( array ($this->s9v_forms->type (), 'Default' ) );
	}
	public function the_contentAction($content) {
		$content = $this->marker ( $this->s9v_forms->type (), $content );
		return $content;
	}
	public function render_marker($match) {
		$this->s9v_forms->set_attributes ( $match );
		do {
			$this->view->Settings = $this->s9v_forms->settings ();
			$this->s9v_forms->set_form ( strtolower ( $this->view->Settings ['form'] ) );
		} while ( strtolower ( $this->s9v_forms->form () ) != strtolower ( $this->view->Settings ['form'] ) );
		$this->fields = new s9v_fields ( $this->application(), $this->s9v_forms->type () ,$this->view->Settings);
		$this->view->save_name = strtolower ( $this->s9v_forms->type () . '_' . $this->view->Settings ['form'] );
		$this->view->expire = date ( 'D, d M Y H:i:0 UTC', time () + (60 * 60 * 24 * 365 * 5) );
		$this->view->DataFields = $this->s9v_forms->DataFields ();
		$this->view->errors = array ();
		$page = "";
		if (is_feed ()) {
			$page = $this->show_feed ();
		} else {
			if (isset ( $_COOKIE [$this->view->save_name] ) && true) {
				$page = $this->answered ();
			} else {
				if ($this->fields->answered ( $this->view->DataFields ) && ! $this->s9v_akismet->check ( $this->view->DataFields,$this->view->Settings )) {
					$page = $this->do_submit ();
				} else {
					$status = $this->form_status ();
					switch ($status) {
						case 'closed' :
							$page = $this->renderScript ( 'Responses/Closed.phtml' );
							break;
						case 'pending' :
							$page = $this->renderScript ( 'Responses/Pending.phtml' );
							break;
						default :
							$page = $this->show_form ();
							break;
					}
				}
			}
		}
		return $page;
	}
	protected function form_status() {
		$return = $this->view->Settings ['status'];
		$this->view->permalink = '';
		if ($this->view->Settings ['submit'] && $return == 'open') {
			if ($this->view->Settings ['post'] != '') {
				$post = $this->get_post_by_title ( $this->view->Settings ['post'] );
				if ($post) {
					$status = $post->post_status;
					switch ($status) {
						case 'pending' :
							$return = 'pending';
							break;
						case 'publish' :
							$return = 'closed';
							break;
						default :
							$return = 'open';
					}
					$this->view->permalink = get_permalink ( $post->ID );
				}
			}
		
		}
		return $return;
	}
	protected function do_submit() {
		$this->submit ();
		return $this->renderScript ( 'Responses/ThankYou.phtml' ).$this->cookie ();
	}
	public function renderScript($script)
	{
		$return = '';
		if(isset($this->view->Settings['responses'][$script]))
		{
			$string = $this->view->render_string($this->view->Settings['responses'][$script]);
			$string = str_replace("\r",'',$string);
			$string = str_replace("\n",'',$string);
			$return = $this->view->render_string($string);
		}
		else
		{
			$return = parent::renderScript($script);
		}
		return $return;	
	}
	protected function unset_post() {
	}
	protected function cookie() {
		$return = '';
		if ($this->view->Settings ['cookie']) {
			$return = "<script>document.cookie = '{$this->view->save_name}=dfsf; expires={$this->view->expire}; path=/'</script>";
		}
		return $return;
	}
	private function show_form() {
		$this->view->akismet = "";
		if ($this->s9v_akismet->check ()) {
			$this->view->akismet = $this->renderScript ( 'Responses/Spam.phtml' );
		}
		if (isset ( $_POST ['usersettings'] ['user'] )) {
			$this->view->user = $_POST ['usersettings'] ['user'];
		}
		$this->view->email = "";
		if (isset ( $_POST ['usersettings'] ['email'] )) {
			$this->view->email = $_POST ['usersettings'] ['email'];
		}
		$this->view->cc = "";
		if (isset ( $_POST ['usersettings'] ['cc'] )) {
			$this->view->cc = ' checked ';
		}
		$this->view->match = "";
		$this->view->id = "id";
		$this->view->ip = "ip";
		$this->view->DataCollection = $this->fields->html ( $this->view->DataFields );
		$this->view->ExtendedInfo = $this->renderScript ( 'Collect/ExtendedInfo.phtml' );
		$page = $this->renderScript ( 'Common/html/form.phtml' );
		return $page;
	}
	private function show_feed() {
		$page = $this->renderScript ( 'Responses/RSSError.phtml' );
		return $page;
	}
	protected function make_table() {
		$data = $this->render_table ();
		$table = new s9v_table ( strtolower ( $this->s9v_forms->type () . '_' . $this->view->Settings ['form'] ) );
		$table->create ( $data, $this->view->Settings );
	}
	protected function make_email() {
		$post=$_POST;
		foreach($this->view->DataFields as $field)
		{
			switch($field['Type'])
			{
				case 'number captcha':
				case 'submit':
					if(isset($post[$field['html_name']]))
					{
						unset($post[$field['html_name']]);
					}
					break;
				case 'name':
				case 'email':
				case 'cc':
					break;
				case 'checkbox':
					if(isset($post[$field['html_name']]))
					{
						if(is_array($post[$field['html_name']]))
						{
							$post[$field['html_name']] = array_flip($post[$field['html_name']]) ;
							foreach(array_keys($post[$field['html_name']]) as $key)
							{
								$post[$field['html_name']][$key] = 1;
							}
						}
					}
				default:		
					if(isset($post[$field['html_name']]))
					{
						$data[$field['Name']] = $post[$field['html_name']];
						unset($post[$field['html_name']]);
					}
			}
		}
		foreach($post as $key=>$value)
		{
			$data[$key] = $value;
		}
		unset($data['info']);
		unset ( $data ['recaptcha_challenge_field'] );
		unset ( $data ['recaptcha_response_field'] );
		$mail = new w11v_Mail ( );
		$this->view->data = $data;
		$page = $this->render_email ();
		$mail = new w11v_Mail ( false );
		$post = get_post ( get_the_id (), ARRAY_A );
		$to=$this->view->Settings['target_email'];
		if($to=='')
		{
			$to = get_the_author_meta ( 'email', $post ['post_author'] );
		}
		$message = $this->render_email ();
		$from = $data ['usersettings'] ['email'];
		$subject = "";
		if (isset ( $data ['Subject'] )) {
			$subject = $data ['Subject'];
		}
		if ($subject == "") {
			$subject = $post ['post_title'];
		}
		
		$from = $data ['usersettings'] ['user'] . " <" . $data ['usersettings'] ['email'] . ">";
		$mail->send ( $to, $subject, $page, $from );
		if (! empty ( $data ['usersettings'] ['cc'] )) {
			$to = $data ['usersettings'] ['email'];
			$mail->send ( $to, $subject, $page, $from );
		}
	}
	protected function render_table() {
		$data = array(); 
		$post=$_POST;
		foreach($this->view->DataFields as $field)
		{
			switch($field['Type'])
			{
				case 'number captcha':
				case 'submit':
					if(isset($post[$field['html_name']]))
					{
						unset($post[$field['html_name']]);
					}
					break;
				case 'name':
				case 'email':
					break;
				case 'checkbox':
					if(isset($post[$field['html_name']]))
					{
						if(is_array($post[$field['html_name']]))
						{
							$post[$field['html_name']] = array_flip($post[$field['html_name']]) ;
							foreach(array_keys($post[$field['html_name']]) as $key)
							{
								$post[$field['html_name']][$key] = 1;
							}
						}
					}
				default:		
					if(isset($post[$field['html_name']]))
					{
						$data[$field['Name']] = $post[$field['html_name']];
						unset($post[$field['html_name']]);
					}
			}
		}
		foreach($post as $key=>$value)
		{
			$data[$key] = $value;
		}
		unset ( $data ['recaptcha_challenge_field'] );
		unset ( $data ['recaptcha_response_field'] );
		return $data;
	}
	protected function render_email() {
		$page = "";
		$this->view->UserInfo = $this->renderScript ( 'Results/TXT/UserInfo.phtml' );
		$this->view->CollectedData = $this->renderScript ( 'Results/TXT/CollectedData.phtml' );
		$this->view->ExtendedInfo = $this->renderScript ( 'Results/TXT/ExtendedInfo.phtml' );
		$page = $this->renderScript ( 'Results/TXT/Results.phtml' );
		return $page;
	}
	protected function make_submission() {
		$post = $this->render_submission ();
		$info = "";
		if (trim ( $post ) != '') {
			if (! isset ( $this->view->Settings ['post'] )) {
				$this->view->Settings ['post'] = 'default';
			}
			$newpost = null;
			if (isset ( $this->view->Settings ['post'] )) {
				$newpost = $this->get_post_by_title ( $this->view->Settings ['post'] );
			}
			if (is_null ( $newpost )) {
				$newpost = get_default_post_to_edit ();
				$newpost->post_content = '<ul class="submit"></ul>';
			}
			// remove get old submissions from post without ul tags
			$pattern = '|<ul class="submit">([\w\W\s\S]*)</ul>|Ui';
			preg_match_all ( $pattern, $newpost->post_content, $matches, PREG_SET_ORDER );
			$old_content = "";
			foreach ( $matches as $match ) {
				$old_content = $match [1];
				break;
			}
			$newpost->post_title = $this->view->Settings ['post'];
			$newpost->post_author = get_the_author_meta ( 'ID' );
			$newpost->post_content = '<ul class="submit">' . $old_content . '<li>' . $post . '</li></ul>';
			$id = wp_update_post ( $newpost );
			add_post_meta ( $id, $_POST ['usersettings'] ['user'], $this->renderScript ( 'Results/Post/ExtendedInfo.phtml' ), false );
		}
		// get_post( $id, OBJECT, 'edit' );
	}
	protected function render_submission() {
		$post=$_POST;
		foreach($this->view->DataFields as $field)
		{
			switch($field['Type'])
			{
				case 'number captcha':
				case 'submit':
					if(isset($post[$field['html_name']]))
					{
						unset($post[$field['html_name']]);
					}
					break;
				case 'name':
				case 'email':
				case 'cc':
					break;
				case 'checkbox':
					if(isset($post[$field['html_name']]))
					{
						if(is_array($post[$field['html_name']]))
						{
							$post[$field['html_name']] = array_flip($post[$field['html_name']]) ;
							foreach(array_keys($post[$field['html_name']]) as $key)
							{
								$post[$field['html_name']][$key] = 1;
							}
						}
					}
				default:		
					if(isset($post[$field['html_name']]))
					{
						$data[$field['Name']] = $post[$field['html_name']];
						unset($post[$field['html_name']]);
					}
			}
		}
		foreach($post as $key=>$value)
		{
			$data[$key] = $value;
		}
		unset ( $data ['info'] );
		unset ( $data ['recaptcha_challenge_field'] );
		unset ( $data ['recaptcha_response_field'] );
		$this->view->data = $data;
		$page = "";
		$this->view->UserInfo = $this->renderScript ( 'Results/HTML/UserInfo.phtml' );
		$this->view->CollectedData = $this->renderScript ( 'Results/HTML/CollectedData.phtml' );
		$this->view->ExtendedInfo = $this->renderScript ( 'Results/HTML/ExtendedInfo.phtml' );
		$page = $this->renderScript ( 'Results/HTML/Results.phtml' );
		return $page;
	}
	protected function get_post_by_title($post_title, $output = OBJECT) {
		global $wpdb;
		$post = $wpdb->get_var ( $wpdb->prepare ( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='post'", $post_title ) );
		if ($post)
			return get_post_to_edit ( $post );
		
		return NULL;
	}
	protected function submit() {
		if ($this->view->Settings ['table']) {
			$this->make_table ();
		}
		if ($this->view->Settings ['email']) {
			$this->make_email ();
		}
		if ($this->view->Settings ['submit']) {
			$this->make_submission ();
		}
	}
	protected function answered() {
		return '';
	}
}
		