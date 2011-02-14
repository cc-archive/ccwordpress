<?php
abstract class w11v_Controller_Action_AdminMenu extends w11v_Controller_Action_Abstract {
	protected $adminMenuTitle = "";
	public function __construct($application) {
		parent::__construct ( $application );
		$this->set_type ( self::WP_DASHBOARD );
		$this->decode_controller ();
	}
	protected function decode_controller() {
		$menus = array ('SandBox', 'Tools', 'SuperAdmin', 'Dashboard', 'Posts', 'Pages', 'Appearance', 'Comments', 'Media', 'Links', 'Plugins', 'Users', 'Settings' );
		//$class = get_class ( $this );
		$class = $this->controllerName ();
		foreach ( $menus as $key ) {
			if (strpos ( $class, $key ) === 0) {
				$this->adminMenuTitle = $key;
				$this->title = substr ( $class, strlen ( $this->adminMenuTitle ) );
				$this->title = explode ( 'Controller', $this->title );
				$this->title = $this->title [0];
				break;
			}
		}
	}
	public function controller() {
		$args = func_get_args ();
		$this->view->_e ( call_user_func_array ( array ('parent', 'controller' ), $args ) );
	}
	protected static $sandbox_shown = false;
	public function setup() {
		//$name = $this->application()->name();
		switch ($this->adminMenuTitle) {
			case 'SuperAdmin' :
				add_submenu_page ( 'wpmu-admin.php', $this->title, $this->title, 'administrator', $this->adminMenuTitle . '_' . $this->title, array ($this, 'controller' ) );
				break;
			case 'SandBox' :
				if ($this->dodebug ()) {
					if ($this->title == $this->adminMenuTitle) {
						if (self::$sandbox_shown === false) {
							add_menu_page ( $this->adminMenuTitle, $this->title, 'administrator', $this->adminMenuTitle, array ($this, 'controller' ) );
							self::$sandbox_shown = true;
						}
					} else {
						add_submenu_page ( $this->adminMenuTitle, $this->adminMenuTitle, $this->title, 'administrator', $this->adminMenuTitle . '_' . $this->title, array ($this, 'controller' ) );
					}
				}
				break;
			default :
				switch ($this->adminMenuTitle) {
					case 'Tools' :
						$function = 'management';
						break;
					case 'Appearance' :
						$function = 'theme';
						break;
					case 'Settings' :
						$function = 'options';
						break;
					default :
						$function = strtolower ( $this->adminMenuTitle );
						break;
				}
				$function = 'add_' . $function . '_page';
				$function ( $this->title, $this->title, 'administrator', $this->adminMenuTitle . '_' . $this->title, array ($this, 'controller' ) );
				break;
		}
	}
	protected function preDispatch() {
		$this->view->args = array ();
		if (count ( func_get_args () ) > 0) {
			$this->view->args = func_get_args ();
		} else {
			$this->view->args [] = null;
		}
		$this->view->icon = '';
		$icons = array ('Dashboard' => 'icon-index', 'Posts' => 'icon-edit', 'Media' => 'icon-upload', 'Links' => 'icon-link-manager', 'Pages' => 'icon-edit-pages', 'Comments' => 'icon-edit-comments', 'Appearance' => 'icon-themes', 'Plugins' => 'icon-plugins', 'Users' => 'icon-users', 'Tools' => 'icon-tools', 'Settings' => 'icon-options-general' );
		if(isset($icons[$this->adminMenuTitle]))
		{
			$this->view->icon = $icons[$this->adminMenuTitle];
		}
		$return = $this->renderScript ( 'Common/header.phtml' );
		if (null !== $return) {
			$this->view->args [0] .= $return;
		}
		$return = ($this->menu ());
		if (null !== $return) {
			$this->view->args [0] .= $return;
		}
		return $this->view->args [0];
	}
	protected function postDispatch() {
		$this->view->args = array ();
		if (count ( func_get_args () ) > 0) {
			$this->view->args = func_get_args ();
		} else {
			$this->view->args [] = null;
		}
		$return = $this->renderScript ( 'Common/footer.phtml' );
		if (null !== $return) {
			$this->view->args [0] .= $return;
		}
		return $this->view->args [0];
	}
	public function menu() {
		if ($this->view->title != $this->view->selected ['title']) {
			$this->view->title .= '&raquo;' . $this->view->selected ['title'];
		}
		$request_uri = explode ( '?', $_SERVER ['REQUEST_URI'] );
		$request_uri = $request_uri [0];
		$this->view->baseUrl = 'http://' . $_SERVER ['HTTP_HOST'] . $request_uri . '?page=' . $_GET ['page'];
		$this->view->items = $this->view->options;
		foreach ( $this->view->items as $key => $value ) {
			if ($value ['hide']) {
				unset ( $this->view->items [$key] );
			}
		}
		return $this->renderScript ( 'Common/menu.phtml' );
	}
}