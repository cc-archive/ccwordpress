<?php
class s9v_field extends w11v_Controller_Action_Action {
/*
 * 
 * to do
  	private $akismet_cache = null;
	private function field_answered($field) {
		$return = true;
				case 'akismet' :
					$return = $this->akismet_check ();
					break;
 */
	private $recaptcha_cache = null;
	public function __construct($application,$settings)
	{
		parent::__construct($application);
		$this->view->settings = $settings;	
	}
	public function new_field($type)
	{
		$return = array(
			'Name'=>'',
			'Type'=>$type,
			'Mandatory'=> false,
			'Values'=>'',
			'Default'=>'',
			'Answer'=>'',
			'Value'=>''
		);
		switch ($type)
		{
			case 'submit':
				$return['Name'] = 'Submit';
				break;
			case 'email':
				$return['Name'] = 'Your Email (Required)';
				$return['Mandatory'] = true;
				break;
			case 'name':
				$return['Name'] = 'Your Name (Required)';
				$return['Mandatory'] = true;
				break;
		}
		$this->value=$return;
		return $this->value;
	}
	protected $captcha=null;
	public function captcha($index)
	{
		if(null===$this->captcha)
		{
			$num1 = mt_rand ( 0, 10 );
			$num2 = mt_rand ( 0, 10 );
			$question = sprintf ( 'The sum of %d and %d is:', $num1, $num2 );
			$answer = $this->captchaEncode ( $num1 + $num2 );
			$this->captcha=array('question'=>$question,'answer'=>$answer);
		}
		return $this->captcha[$index];
	}
	private function captchaEncode($value) {
		$salt = 'sdkjghfsdjkghahsdlfkj';
		return md5 ( $salt . $value );
	}
	public function html()
	{
		$this->view->field=$this;
		$script = null;
		switch ($this->settings['Type']) {
			case 'text':
			case 'name':
			case 'email':
				$script =  'input';
				if(count($this->values())>1)
				{
					$script = 'select';
				}
				break;
			case 'radio':
				$script =  'multi';
				break;
			case 'submit':
				$this->s9v_forms = new s9v_forms ( $this->application() );
				$this->set_template_folders ( array ($this->s9v_forms->type (), 'Default' ) );
				$this->view->private = $this->renderScript ( 'Responses/Private.phtml' );
				$script =  'submit';
				break;
			case 'textarea':
				$script =  'textarea';
				break;
			case 'link':
				$script =  'link';
				break;
			case 'number captcha':
				$script =  'captcha';
				break;
			case 'recaptcha':
				break;
			default:
				$script = 'multi';
		}
		$this->view->input = '';
		$this->view->input .= $this->label_html('pre');
		if(null!==$script)
		{
			$this->view->input .=$this->renderScript ("Common/html/{$script}.phtml");
		}
		else if(method_exists($this,$this->settings['Type'].'_html'))
		{
			$func = $this->settings['Type'].'_html'; 
			$this->view->input .= $this->$func();
		}
		$this->view->input .= $this->label_html('post');
		$this->view->class='noerror';
		if(!$this->answered())
		{
			$this->view->class='error';
		}
		$return = $this->renderScript ("Common/html/fieldset.phtml");
		return $return;
	}
	public function recaptcha_html()
	{
		$recaptcha = new s9v_recaptcha ( $this->application () );
		return $recaptcha->get_html ();
	}
	public function values()
	{
		$return = '';
		switch($this->settings['Type'])
		{
			case 'cc':
				$return = array($this->settings['Name']);
				break;
			case 'number captcha':
				$return = $this->captcha('answer');
				break;
			case 'number captcha':
				$return = $this->captcha('answer');
				break;
			default:
				$return = $this->settings['Values'];
				$return = str_replace("\r",'',$return);
				$return = explode("\n",$return);
		}
		return $return;
	}
	public function html_type()
	{
		switch ($this->settings['Type']) {
			case 'checkbox':
			case 'cc':
				return 'checkbox';
				break;
			case 'radio':
				return 'radio';
				break;	
			case 'submit':
				return 'submit';
				break;
			default:
				return 'text';
		}
	}
	public function html_value($index=null)
	{
		$return = null;
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			switch ($this->settings['Type']) {
				case 'email':
					if(isset($_POST['usersettings']['email']))
					{
						$return=$_POST['usersettings']['email'];
					}
					break;
				case 'name':
					if(isset($_POST['usersettings']['user']))
					{
						$return=$_POST['usersettings']['user'];
					}
					break;
				case 'cc':
					if(isset($_POST['usersettings']['cc']))
					{
						$return=$_POST['usersettings']['cc'];
					}
					break;
				case 'number captcha':
					if(isset($_POST['captcha']))
					{
						$return=$_POST['captcha'];
					}
					break;
				case 'submit':
					break;
				default:
					$return = '';
					if(isset($_POST[$this->html_name(true)]))
					{
						$return=$_POST[$this->html_name(true)];
					}
			}
		}
		if(null===$return)
		{
			switch ($this->settings['Type']) {
				case 'email':
					$return=$this->application ()->info ()->wp_user ( 'user_email' );
					break;
				case 'name':
					$return=$this->application ()->info ()->wp_user ( 'display_name' );
					break;
				case 'submit':
					$return = $this->settings['Name'];
					break;
				default:
					return '';
			}
		}
		if(null!==$index && isset($return[$index]))
		{
			$return = $return[$index];
		}
		return $return;
	}
	public function html_checked()
	{
		return '';
	}
	protected $settings = null;
	public function settings($settings=null)
	{
		if(null!==$settings)
		{
			$this->settings=$this->prepare_settings($settings);
			$this->settings ['error'] = !$this->answered ( );
			$this->settings ['HTML'] = $this->html();
			$this->settings ['Value'] = $this->html_value();
			$this->settings ['html_name'] = $this->html_name(true);
		}
		return $this->settings;
	}
	protected function prepare_settings($settings)
	{
		$return = null;
		if(isset($settings['Type']))
		{
			$return = $this->new_field($settings['Type']);
		}
		else
		{
			$return = $this->new_field('text');
		}
		if(is_array($settings))
		{
			foreach($settings as $key=>$value)
			{
				if(isset($return[$key]))
				{
					$return[$key]=$value;
				}
			}
		}
		switch ($settings['Type']) {
			case 'submit':
				$return['Mandatory']=false;
				break;
		}
		return $return;
	}
	public function answered()
	{
		$return = true;
		if($_SERVER ['REQUEST_METHOD'] == 'POST' && isset ( $this->settings ['Mandatory'] ) && strtolower ( $this->settings ['Mandatory'] ) == 'yes')
		{
			$value=$this->html_value();
			switch($this->settings['Type'])
			{
				case 'number captcha':
					$return= ($this->captchaEncode($this->html_value('response')) == $this->html_value('answer'));
					break;
				case 'link' :
					if(isset($value ['URL']) && isset($value ['Caption']))
					{
						$return = ($value ['URL'] != '' && $value ['Caption'] != '');
					}
					break;
				case 'recaptcha' :
					$return = false;
					if(isset($_POST ['recaptcha_challenge_field']) && isset($_POST ['recaptcha_response_field']))
					{
						$recaptcha = new s9v_recaptcha ( $this->application () );
						$return = $recaptcha->is_valid ( $_POST ['recaptcha_challenge_field'], $_POST ['recaptcha_response_field'] );
					}
					break;
				case 'submit' :
					break;
				default:
					$return=($value!='');
			}
		}
		return $return;
	}
	public function label_html($position)
	{
		$return = '';
		if(($this->view->settings['label']=='top' && $position=='pre') || ($this->view->settings['label']=='side' && $position=='post'))
		{
			$this->view->position = $position;
			$return = $this->renderScript ( 'Common/html/label.phtml' );
		}
		return $return;
	}
	public function label_text()
	{
		$return ='';
		switch($this->settings['Type'])
		{
			case 'cc':
			case 'submit':
				$return ='';
				break;
			case 'number captcha':
				$return =$this->captcha('question');
				break;
			default:
				$return = $this->settings['Name'];
		}
		return $return;
	}
	public function html_name($safe=false)
	{
		$name = '';
		switch ($this->settings['Type']) {
			case 'email':
				$name = 'usersettings'; 
				if(!$safe)
				{
					$name.='[email]';
				} 
				break;
			case 'name':
				$name = 'usersettings';
				if(!$safe)
				{
					$name.='[user]';
				} 
				break;
			case 'number captcha':
				$name = 'captcha';
				if(!$safe)
				{
					$name.='[response]';
				} 
				break;
			case 'cc':
				$name = 'usersettings';
				if(!$safe)
				{
					$name.='[cc]';
				} 
				break;
			case 'submit':
				$name = 'submit';
				if(!$safe)
				{
					$name.='[submit]';
				} 
				case 'checkbox':
				$name = md5($this->settings['Name']);
				if(count($this->values())>1 && !$safe)
				{
					$name.='[]';
				}
				break;
			default:
				$name = md5($this->settings['Name']);
				break;
		}
		return $name;
	}
	public function html_id()
	{
		$name = md5($this->settings['Name']);
		return $name;
	}
}