<?php
class s9v_table extends w11v_Table {
	protected $table_name = "";
	protected $fields = array ();
	protected $keys = array ();
	public function option_count($field) {
		$columns = $this->show_columns ();
		$answers = array ();
		foreach ( $columns as $column ) {
			if ($column ['Key'] != 'PRI') {
				$answers [] = $column ['Field'];
			}
		}
		$field ['Values'] = explode ( "\n", $field ['Values'] );
		$sql = array ();
		foreach ( $field ['Values'] as $value ) {
			if (in_array ( $value, $answers )) {
				$sql [] = "count(`$value`) '{$value}'";
			} else {
				$sql [] = "0 '{$value}'";
			}
		}
		$xsql = "SELECT %s FROM `%s`;";
		$xsql = sprintf ( $xsql, implode ( ',', $sql ), $this->name () );
		$results = $this->execute ( $xsql );
		if (count ( $results ) >= 1) {
			$field ['Values'] = array ();
			foreach ( $results [0] as $key => $value ) {
				$field ['Values'] [$key] = $value;
			}
		} else {
			$field ['Values'] = array_flip ( $field ['Values'] );
			foreach ( $field ['Values'] as $key => $value ) {
				$field ['Values'] [$key] = 0;
			}
		}
		return $field;
	}
	public function create($fields,$settings) {
		$newfields = array ();
		foreach ( $fields as $key => $value ) {
			if (is_array ( $value )) {
				foreach ( $value as $key2 => $value2 ) {
					$newfields [urldecode ( $key ) . '[' . urldecode ( $key2 ) . ']'] = $value2;
				}
			} else {
				$newfields [urldecode ( $key )] = $value;
			}
		}
		$fields = $newfields;
		$newfields = null;
		$this->fields = array ('id' => array ('type' => 'bigint(20)', 'null' => false, 'extra' => 'AUTO_INCREMENT' ) );
		$this->keys = array ('primary' => 'id' );
		if (! is_null ( $this->fields )) {
			$this->create_table ( $this->fields, $this->keys );
			$results = $this->show_columns ();
			$columns = array ();
			foreach ( $results as $result ) {
				$columns [] = $result ['Field'];
			}
			foreach ( $fields as $key => $value ) {
				$this->field($key);
				$key=trim($key,"`");
				if (! in_array ( $key, $columns ) && $key != '') {
					$type = 'text';
					switch ($key) {
						case 'info[form_sent]' :
						case 'info[form_received]' :
							$type = 'datetime';
							break;
						case 'info[wp_user]' :
							$type = 'bigint(20)';
					}
					$this->alter_table ( $key, $type );
				}
			}
			if (isset ( $fields ['info[form_received]'] )) {
				$fields ['info[form_received]'] = date ( 'Y-m-d G:i:s' );
			}
			if (isset ( $fields ['info[wp_user]'] )) {
				if ($fields ['info[wp_user]'] != 0 && $settings['overwrite']) {
					$count = $this->count_records ( '*', "`info[wp_user]`='" . $fields ['info[wp_user]'] . "'" );
					if ($count > 0) {
						return;
					}
				}
			}
			$this->insert ( $fields );
		}
	}
	public function list_tables() {
		$results = $this->show_tables ( $this->name () . '_%' );
		$return = array ();
		foreach ( $results as $result ) {
			foreach ( $result as $field ) {
				$name = substr ( $field, strlen ( $this->name () ) + 1 );
				$count = $this->count_records ( '*', null, $field );
				$return [] = array ('name' => $name, 'table' => $field, 'records' => $count );
			}
		}
		return $return;
	}
	public function __construct($name = '', $blog_id = null) {
		parent::__construct ( $blog_id, $name );
	}
}