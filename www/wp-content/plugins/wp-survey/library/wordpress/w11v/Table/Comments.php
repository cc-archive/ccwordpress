<?php
class w11v_Table_Comments extends w11v_Table {
	public function name() {
		return $this->wpdb ()->comments;
	}
}