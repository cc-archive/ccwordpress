<?php

class Search_Log
{
	function Search_Log ($data = '')
	{
		if (is_array ($data))
		{
			foreach ($data AS $key => $value)
				$this->$key = $value;
				
			$this->searched_at = mysql2date ('U', $this->searched_at);
		}
	}
	
	function ip ()
	{
		return sprintf ('<a href="http://geomaplookup.cinnamonthoughts.org/?ip=%s">%s</a>', long2ip ($this->ip), long2ip ($this->ip));
	}
	
	function phrase ()
	{
		return sprintf ('<a href="%s" target="_blank">%s</a>', get_bloginfo ('home').'?s='.urlencode ($this->phrase), $this->phrase);
	}
	
	function referrer ()
	{
		if ($this->referrer)
		{
			$engine = new Search_Engine ($this->referrer);
			return sprintf ('<a href="%s">%s</a>', $this->referrer, ucfirst ($engine->engine));
		}
		return '';
	}
	
	function record ($phrase, $referrer = '')
	{
		global $wpdb;
		
		$phrase   = wpdb::escape (trim ($phrase));

		if (isset ($_SERVER['REMOTE_ADDR']))
		  $ip = $_SERVER['REMOTE_ADDR'];
		else if (isset ($_SERVER['HTTP_X_FORWARDED_FOR']))
		  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	
		$ip = sprintf ('%u', ip2long ($ip));
		
		if ($referrer)
		{
			if ($referrer->engine != 'local')
			{
				$ref = wpdb::escape ($referrer->referrer);
				$wpdb->query ("INSERT INTO {$wpdb->prefix}search_phrases (phrase,ip,searched_at,referrer) VALUES ('$phrase','$ip',NOW(),'$ref')");
			}
		}
		else
			$wpdb->query ("INSERT INTO {$wpdb->prefix}search_phrases (phrase,ip,searched_at) VALUES ('$phrase','$ip',NOW())");
	}
	
	function get (&$pager)
	{
		global $wpdb;
		
		$rows = $wpdb->get_results ("SELECT SQL_CALC_FOUND_ROWS * FROM {$wpdb->prefix}search_phrases ".$pager->to_limits ('', array ('phrase')), ARRAY_A);
		$pager->set_total ($wpdb->get_var ("SELECT FOUND_ROWS()"));
		
		$data = array ();
		if ($rows)
		{
			foreach ($rows AS $row)
				$data[] = new Search_Log ($row);
		}
		
		return $data;
	}
	
	function delete_all ()
	{
		global $wpdb;
		
		$wpdb->query ("DELETE FROM {$wpdb->prefix}search_phrases");
	}
}

?>