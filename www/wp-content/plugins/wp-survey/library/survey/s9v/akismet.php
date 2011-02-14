<?php
class s9v_akismet extends w11v_Table_Options {
	public function defaults() {
		return array ('api_key' => '' );
	}
	private $key = 'akismet_keys';
	public function __construct($application, $blog_id = null) {
		parent::__construct ( $blog_id );
		$this->set_key ( array ('akismet_keys' ) );
		$this->set_application ( $application );
	}
	public function verify_key($key) {
		$api_host = 'rest.akismet.com';
		$api_port = 80;
		$blog = urlencode ( get_option ( 'home' ) );
		$response = $this->http_post ( "key=$key&blog=$blog", $api_host, '/1.1/verify-key', $api_port );
		if ('valid' == $response [1]) {
			return true;
		} else {
			
			return false;
		}
	}
	private function http_post($request, $host, $path, $port = 80) {
		global $wp_version;
		$user_agent = "WordPress/$wp_version | " . $this->application ()->settings ()->name . '/' . $this->application ()->settings ()->version;
		$http_request = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=" . get_option ( 'blog_charset' ) . "\r\n";
		$http_request .= "Content-Length: " . strlen ( $request ) . "\r\n";
		$http_request .= "User-Agent: $user_agent\r\n";
		$http_request .= "\r\n";
		$http_request .= $request;
		$response = '';
		if (false !== ($fs = @fsockopen ( $host, $port, $errno, $errstr, 3 ))) {
			fwrite ( $fs, $http_request );
			while ( ! feof ( $fs ) ) {
				$response .= fgets ( $fs, 1160 ); // One TCP-IP packet
			}
			fclose ( $fs );
			$response = explode ( "\r\n\r\n", $response, 2 );
		}
		return $response;
	}
	private $spam = null;
	private function _check() {
		$keys = $this->get ( $this->key );
		$key = $keys ['api_key'];
		$api_host = $key . '.rest.akismet.com';
		$api_port = 80;
		$commentdata = array ('permalink' => get_permalink (), 'comment_author' => $_POST ['usersettings'] ['user'], 'comment_author_email' => $_POST ['usersettings'] ['email'], 'comment_content' => $_POST ['akismet'] );
		$comment = $commentdata;
		$comment ['user_ip'] = urlencode ( $_SERVER ['REMOTE_ADDR'] );
		$comment ['user_agent'] = urlencode ( $_SERVER ['HTTP_USER_AGENT'] );
		$comment ['referrer'] = urlencode ( $_SERVER ['HTTP_REFERER'] );
		$comment ['blog'] = urlencode ( get_option ( 'home' ) );
		$comment ['blog_lang'] = urlencode ( get_locale () );
		$comment ['blog_charset'] = urlencode ( get_option ( 'blog_charset' ) );
		$ignore = array ('HTTP_COOKIE', 'HTTP_COOKIE2', 'PHP_AUTH_PW' );
		foreach ( $_SERVER as $key => $value )
			if (! in_array ( $key, $ignore ) && is_string ( $value ))
				$comment ["$key"] = $value;
			else
				$comment ["$key"] = '';
		$query_string = '';
		foreach ( $comment as $key => $data )
			$query_string .= $key . '=' . urlencode ( stripslashes ( $data ) ) . '&';
		$response = $this->http_post ( $query_string, $api_host, '/1.1/comment-check', $api_port );
		$commentdata ['akismet_result'] = $response [1];
		$this->spam = ($commentdata ['akismet_result'] == 'true');
		return $this->spam;
	}
	public function check($fields = array(),$settings=null) {
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			if (null === $this->spam) {
				$keys = $this->get ( $this->key );
				if (isset ( $keys ['api_key'] ) && $keys ['api_key'] != '' && $settings['akismet']) {
					$akismet = array ();
					foreach ( $fields as $field ) {
						if ($field ['Type'] == 'textarea' && isset ( $_POST [$field ['Name']] )) {
							$akismet [] = $_POST [$field ['Name']];
						}
					}
					$_POST ['akismet'] = implode ( '\n', $akismet );
					$this->_check ();
				}
			}
		}
		return $this->spam;
	}
}