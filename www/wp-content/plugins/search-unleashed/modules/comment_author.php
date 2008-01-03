<?php

class Search_Comment_Author extends Search_Module
{
	function gather_for_comment ($data)
	{
		return apply_filters ('comment_author', apply_filters ('get_comment_author', $data->comment_author));
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
			
				return '<p><strong>'.__ ('Comment author', 'search-unleashed').':</strong> '.$highlight->get ().'</p>';
			}
		}
		
		return '';
	}
	
	function name () { return __ ('Comment author', 'search-unleashed'); }
}

?>