<?php

class SearchParser
{
	var $original = '';
	var $small = array ();
	var $full  = array ();
	var $logical_and = array ();
	var $logical_or = array ();
	
	var $is_regex = false;
	
	function SearchParser ($request)
	{
		$request = stripslashes ($request);
		$request = str_replace ('"', "'", $request);
		
		// Remove any options
		// $request = preg_replace_callback ('/regex:\/(.*?)\/\s*/', array (&$this, 'search_regex'), $request);
		// $request = str_replace ('regex:', '', $request);
		
		$this->original = trim ($request);
		
		// Extract any exact words
		$request = preg_replace_callback ("/'(.*?)'/", array (&$this, 'exact_words'), $request);		
	
		// Extract any logical words
		$request = preg_replace_callback ('/(\w*)\s*AND\s*(\w*)/', array (&$this, 'logical_and'), $request);
		$request = preg_replace_callback ('/(\w*)\s*OR\s*(\w*)/', array (&$this, 'logical_or'), $request);
		
		$this->logical_and = array_filter ($this->logical_and);
		$this->logical_or  = array_filter ($this->logical_or);
		
		$words = preg_split ('/[\s,]+/', $request);
		
		// Split the words into small and full
		foreach ($words AS $word)
		{
			if (strlen ($word) > 4)
				$this->full[] = $word;
			else
				$this->small[] = $this->strip_fulltext ($word);
		}
		
		$this->full = array_filter ($this->full);
		$this->small = array_filter ($this->small);
	}
	
	function search_regex ($word)
	{
		$this->regex[] = $word[1];
		return '';
	}
	
	function logical_and ($matches)
	{
		$this->logical_and[] = $matches[1];
		$this->logical_and[] = $matches[2];
		return '';
	}

	function logical_or ($matches)
	{
		$this->logical_or[] = $matches[1];
		$this->logical_or[] = $matches[2];
		return '';
	}
	
	function exact_words ($matches)
	{
		$this->small[] = $matches[1];
		return '';
	}
	
	function get_sql ()
	{
		global $wpdb;
		
		$sql = array ();
		
		if (count ($this->small) > 0)
		{
			foreach ($this->small AS $small)
			{
				$small = wpdb::escape ($small);
				$str = "({$wpdb->prefix}search.content LIKE '%$small%' OR {$wpdb->prefix}search.priority LIKE '%$small%')";
				$sql[] = $str;
			}
		}
		
		if (count ($this->full) > 0 || count ($this->logical_and) > 0 || count ($this->logical_or) > 0)
		{
			$tmp = $this->full;
			if (count ($this->logical_and) > 0)
				$tmp = array_merge ($tmp, array ('(+'.implode (' +', $this->logical_and).')'));

			if (count ($this->logical_or) > 0)
				$tmp = array_merge ($tmp, array ('('.implode (' ', $this->logical_or).')'));
			
			$full = wpdb::escape (implode (' ', $tmp));
			$sql[] = "MATCH(content,priority) AGAINST ('$full' IN BOOLEAN MODE)";
		}

		if (count ($this->regex) > 0)
		{
			foreach ($this->regex AS $regex)
				$sql[] = "content REGEXP '".wpdb::escape ($regex)."'";
		}
		
		return implode (' OR ', $sql);
	}
	
	function get_search ()
	{
		return str_replace ("'", '', $this->original);
	}
	
	function get_full_search ()
	{
		return implode (' ', $this->full);
	}
	
	
	function strip_fulltext ($word)
	{
		if ($word === '-')
			return '';
		$word = preg_replace ('@[\\\=\!\|\:\{\}\(\)\+<>~\.\[\]\^\$]@', '', $word);
		$word = str_replace ('\\*', '\w*', preg_quote ($word, '/'));
		return $word;
	}
	
	function get_terms ()
	{
		$words = array_map (array (&$this, 'strip_fulltext'), $this->small);
		$words = array_merge ($words, array_map (array (&$this, 'strip_fulltext'), $this->full));
		$words = array_merge ($words, array_map (array (&$this, 'strip_fulltext'), $this->logical_and));
		$words = array_merge ($words, array_map (array (&$this, 'strip_fulltext'), $this->logical_or));
		return $words;
	}
	
	function strip_for_display ($word)
	{
		if ($word === '-')
			return '';
		return str_replace ('\w*', '*', $word);
	}
	
	function get_terms_for_display ()
	{
		return array_map (array (&$this, 'strip_for_display'), $this->get_terms ());
	}
	
	function has_terms ()
	{
		return (count ($this->full) > 0 || count ($this->small) > 0 || count ($this->logical_and) > 0 || count ($this->logical_or) > 0);
 	}
}
?>