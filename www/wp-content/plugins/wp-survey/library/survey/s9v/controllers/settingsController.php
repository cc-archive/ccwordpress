<?php
class s9v_controllers_settingsController extends w11v_Controller_Action_AdminMenu {
	public function ControllerMeta() {
		return 'Settings' . $this->application ()->settings ()->name . 'Controller';
//		return 'SettingsFormPluginsController';
	}
	protected $s9v_forms = null;
	public function SupportActionMeta($return)
	{
		$return['title']='Support Forum';
		$return['url'] = "http://wordpress.org/tags/".$this->application()->Settings()->wpslug."?forum_id=10";
		$return ['priority'] = 10;
		return $return;
	}
	public function SupportAction()
	{
	}
	public function PluginActionMeta($return)
	{
		$return['title']='Plugin Site';
		$return['url'] = $this->application()->Settings()->uri;
		$return ['priority'] = 10;
		return $return;
	}
	public function PluginAction()
	{
	}
	public function DonateActionMeta($return)
	{
		$return['url'] = $this->application()->Settings()->donate_link;
		$return ['priority'] = 10;
		return $return;
	}
	public function DonateAction()
	{
	}
	public function __construct($application) {
		$this->s9v_forms = new s9v_forms ( $application );
		parent::__construct ( $application );
		$this->view->updated_class = 'updated';
	}
	public function AkismetActionMeta($return) {
		$options = $this->s9v_forms->settings ();
		if (! $options ['show_akismet']) {
			$return ['title'] = '';
		}
		return $return;
	}
	public function AkismetAction($content) {
		$akismet = new s9v_akismet ( $this->application () );
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			if (! empty ( $_POST ['api_key'] ) && ! $akismet->verify_key ( $_POST ['api_key'] )) {
				$_POST ['api_key'] = '';
				$this->view->updated = 'Invalid Key: Key has been removed';
				$this->view->updated_class = 'error';
			
			} else {
				$this->view->updated = 'Settings Updated';
				$this->view->updated_class = 'updated';
			}
		}
		$this->view->data = $akismet->post ();
		$page = $this->renderScript ( 'Common/akismet.phtml' );
		return $content . $page;
	}
	public function reCAPTCHAActionMeta($return) {
		$options = $this->s9v_forms->settings ();
		if (! in_array ( 'recaptcha', explode ( ',', $options ['types'] ) )) {
			$return ['title'] = '';
		}
		return $return;
	}
	public function reCAPTCHAAction($content) {
		$recaptcha = new s9v_recaptcha ( $this->application () );
		$this->view->data = $recaptcha->post ();
		$this->view->signup_url = $recaptcha->signup_url ();
		$page = $this->renderScript ( 'Common/recaptcha.phtml' );
		return $content . $page;
	}
	public function FormsActionMeta($return) {
		$return ['priority'] = - 2;
		return $return;
	}
	public function FormsAction($content) {
		$ru = explode('?',$_SERVER ['REQUEST_URI']);
		$this->view->url = 'http://' . $_SERVER ['HTTP_HOST'] .$ru[0].'?page='. $_GET ['page'];
		if (! empty ( $_POST ['new'] )) {
			$tag = b11v_Tag::instance ();
			$diags = $tag->get('diag',stripslashes($_POST ['new']),true);
			if(count($diags)>0)
			{
				foreach($diags as $diag)
				{
					$settings=$diag['innerhtml'];
					$settings=str_replace("\r",'',$settings);
					$settings=str_replace("\n",'',$settings);
					$settings=trim($settings);
					$check = md5($settings.$this->application()->Settings()->name);
					if($check==$diag['attributes']['hash'])
					{
						$settings=base64_decode($settings);
						$settings=gzuncompress($settings);
						$settings=unserialize($settings);
						$this->s9v_forms->copy ( $diag['attributes']['form'], $settings );
					}
				}
			}
			else
			{
				$this->s9v_forms->copy ( $_POST ['new'], $_POST ['source'] );
			}
		}
		if (isset ( $_POST ['delete'] )) {
			$this->s9v_forms->delete ( $_POST ['delete'] );
		}
		if (isset ( $_POST ['delete_table'] )) {
			$table = new w11v_table ();
			foreach($_POST ['delete_table']  as $key=>$value)
			{
				$table->drop($key);
			}
		
		}
		$table = new s9v_table ( strtolower ( $this->application ()->Settings ()->name ));
		$tables = $table->list_tables ();
		$forms = $this->s9v_forms->forms ();
		$this->view->forms = array ();
		foreach($forms as $form)
		{
			$this->view->forms[$form]['name']=$form;
		}
		foreach($tables as $table)
		{
			$table['url'] = $this->control_url ( '/' . $this->s9v_forms->type () . '/' . $table ['name'] . '.csv' );
			if(isset($this->view->forms[$table['name']]))
			{
				$this->view->forms[$table['name']]=$table;
			}			
			else
			{
				$table['name']='';
				$this->view->forms[]=$table;
			}
		}
		$this->view->show_tables=(count($tables)>0);
		$this->view->options = $this->s9v_forms->settings ();
		$this->view->type = $this->s9v_forms->type ();
		$this->view->type = strtolower ( $this->s9v_forms->type () );
		$page = $this->renderScript ( 'Common/Forms.phtml' );
		$page =  $this->updated ( 'Forms Updated' ) . $page;
		return $content . $page;
	}
	public function save_url($form)
	{
		$return = $this->control_url ( '/' . $this->application()->Settings()->name . '/' . $form . '.sav' );
		return $return;
	}
	public function FormsEditActionMeta($return)
	{
		if(isset($_GET['edit']))
		{
			$return['title'] = 'Forms&raquo;Manage&raquo;'.$_GET['edit'];
		}
		$return['hide'] = true;
		return $return;
	}
	public function FormsEditAction($content)
	{
		$ru = explode('?',$_SERVER ['REQUEST_URI']);
		$this->view->url = 'http://' . $_SERVER ['HTTP_HOST'] .$ru[0].'?page='. $_GET ['page'];
		$this->s9v_forms->set_form ( $_GET ['edit'] );
		$this->view->options = $this->s9v_forms->post ();
		$this->view->options = $this->s9v_forms->settings ();
		$this->view->options ['fields'] = $this->s9v_forms->DataFields ();
		if ($this->view->options ['settings_columns'] ['delete'] != '') {
			$this->view->options ['fields'] [] = array ('Name' => '', 'Type' => '', 'Mandatory' => '', 'Values' => '', 'Default' => '', 'Answer' => '' );
		}
		$this->view->showMail = false;
		$table = new s9v_table ( strtolower ( $this->application ()->Settings ()->name ));
		$tables = $table->list_tables ();
		$this->view->tables = array();
		foreach ( $tables as $key => $value ) {
			$this->view->tables[$value['name']] = $value;
			$this->view->tables [$value['name']] ['url'] = $this->control_url ( '/' . $this->s9v_forms->type () . '/' . $value ['name'] . '.csv' );
		}
		if(isset($this->view->tables[$_GET['edit']]))
		{
			$this->view->table=$this->view->tables[$_GET['edit']];
		}
		$table = new s9v_table ( strtolower ( $this->application ()->Settings ()->name ).'_'.$_GET['edit'] );
		$this->view->table_name = $table->name();
		$this->view->tag = strtolower ( $this->s9v_forms->type () );
		$this->view->types = explode ( ',', $this->view->options ['types'] );
		sort ( $this->view->types );
		$this->view->responses = array(
		'Responses/ThankYou.phtml' => array('title'=>'Thank you','info'=>''),
		'Responses/Closed.phtml' => array('title'=>'Form Closed','info'=>''),
		'Responses/Pending.phtml' => array('title'=>'Form Pending','info'=>''),
		'Responses/RSSError.phtml' => array('title'=>'Message to display if the form is viewed from RSS','info'=>'Forms do not work well inside RSS, so subscribers can be asked to visit the site'),
		'Responses/Spam.phtml' => array('title'=>'Akismet Warning','info'=>''),		
		'Responses/Private.phtml' => array('title'=>'Private Message','info'=>''),		
		);
		$this->view->page = "";
		$this->view->page .= $this->renderScript ( 'Common/FormsEdit/General.phtml' );
		if($this->application()->Settings()->name!='QuizMe')
		{
			$this->view->page .= $this->renderScript ( 'Common/FormsEdit/DataCollection.phtml' );
		}
		if($this->view->options['graphics'])
		{
			$this->view->page .= $this->renderScript ( 'Common/FormsEdit/Graphics.phtml' );
		}
		$this->view->page .= $this->renderScript ( 'Common/FormsEdit/FieldDefinitions.phtml' );
		$this->view->page .= $this->renderScript ( 'Common/FormsEdit/Responses.phtml' );
		
		$page = $this->renderScript ( 'Common/FormsEdit.phtml' );
		$page =  $this->updated ( 'Fields Updated' ) . $page;
		return $content.$page;
	}
}		