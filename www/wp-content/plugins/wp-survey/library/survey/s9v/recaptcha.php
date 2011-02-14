<?php
class s9v_recaptcha extends w11v_Table_Options {
	private $API_SERVER = "http://www.google.com/recaptcha/api";
	private $API_SECURE_SERVER = "https://www.google.com/recaptcha/api";
	private $VERIFY_SERVER = "http://www.google.com";
	public function defaults() {
		return array ('private' => '', 'public' => '', 'theme' => 'white' );
	}
	public function themes() {
		return array ('blackglass', 'clean', 'red', 'white' );
	}
	public function signup_url() {
		$http = new b11v_http();
		return "https://www.google.com/recaptcha/admin/create?" . $http->data ( array ('domains' => get_option ( 'home' ), 'app' => $this->application ()->settings ()->name ) );
	}
	private $key = 'recaptcha_keys';
	public function __construct($application, $blog_id = null) {
		parent::__construct ( $blog_id );
		$this->set_application ( $application );
		$this->set_key ( array ('recaptcha_keys' ) );
	}
	public function get_html() {
		$keys = $this->get ( $this->key );
		$pubkey = $keys ['public'];
		if ($pubkey == null || $pubkey == '') {
			return "";
		}
		$server = $this->API_SERVER;
		$errorpart = "";
		$theme = ' <script type="text/javascript">
 var RecaptchaOptions = {
    theme : "' . $keys ['theme'] . '"
 };
 </script>';
		return $theme . '<script type="text/javascript" src="' . $server . '/challenge?k=' . $pubkey . $errorpart . '"></script>

	<noscript>
  		<iframe src="' . $server . '/noscript?k=' . $pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>';
	}
	private static $is_valid_cache = array();
	public function is_valid($challenge, $response)
	{
		if(!isset(s9v_recaptcha::$is_valid_cache[$challenge]))
		{
			$returned = $this->check_answer ( $_POST ['recaptcha_challenge_field'], $_POST ['recaptcha_response_field'] );
			s9v_recaptcha::$is_valid_cache[$challenge] = $returned->is_valid;
		}
		return s9v_recaptcha::$is_valid_cache[$challenge];
	}
	public function check_answer($challenge, $response) {
		$keys = $this->get ( $this->key );
		$privkey = $keys ['private'];
		$remoteip = $_SERVER ['REMOTE_ADDR'];
		//discard spam submissions
		if ($challenge == null || strlen ( $challenge ) == 0 || $response == null || strlen ( $response ) == 0) {
			$this->is_valid = false;
			$this->error = 'incorrect-captcha-sol';
			return $this;
		}
		$http = new b11v_http($this->VERIFY_SERVER. "/recaptcha/api/verify");
		$http->addHeader('User-Agent', $this->application ()->settings ()->name . '/' . $this->application ()->settings ()->version);
		$http->data(array ('privatekey' => $privkey, 'remoteip' => $remoteip, 'challenge' => $challenge, 'response' => $response ));
		$http->method('POST');
		$return = $http->get();		
		$answers = explode ( "\n", $return  );
		$this->is_valid = (trim ( $answers [0] ) == 'true');
		return $this;
	}
	private $is_valid = null;
}