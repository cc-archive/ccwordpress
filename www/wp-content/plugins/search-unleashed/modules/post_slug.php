<?php

class Search_Post_Slug extends Search_Module
{
	function gather_for_priority ($data)
	{
		return implode (' ', explode ('/', $this->get_permalink ($data->ID)));
	}
	
	function get_permalink ($id)
	{
		return str_replace (get_bloginfo ('home'), '', get_permalink ($id));	
	}
	
	function highlight ($post, $words, $content)
	{
		$highlight = new Highlighter ($this->get_permalink ($post->ID), $words, true);

		if ($highlight->has_matches ())
		{
			$highlight->mark_words ();
			
			return '<p><strong>'.__ ('URL', 'search-unleashed').':</strong> '.$highlight->get ().'</p>';
		}
		
		return '';
	}
	
	function name () { return __ ('Post slug', 'search-unleashed'); }
}

?>