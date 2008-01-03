<?php

class Search_Comment_URL extends Search_Module
{
	function gather_for_comment ($data)
	{
		return apply_filters ('comment_url', $data->comment_author_url);
	}
	
	function highlight ($post, $words, $content)
	{
		if ($post->comment_id > 0)
		{
			$comment = get_comment ($post->comment_id);
			
			$highlight = new Highlighter ($this->gather_for_comment ($comment), $words, true);
			if ($highlight->has_matches ())
			{
				$highlight->mark_words ();
			
				return '<p><strong>'.__ ('Comment author URL', 'search-unleashed').':</strong> '.$highlight->get ().'</p>';
			}
		}
		
		return '';
	}
	
	function name () { return __ ('Comment author URL', 'search-unleashed'); }
}

?>