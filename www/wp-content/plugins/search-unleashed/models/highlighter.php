<?php

// match search terms first into positions


class Highlighter
{
	var $first_match = -1;
	
	var $text  = '';
	var $words = array ();
	
	function Highlighter ($text, $search, $strip = false)
	{
		if ($strip)
			$this->text = $this->strip ($text);
		else
			$this->text = $text;
			
		$this->matches     = 0;
		$this->first_match = strlen ($this->text);
		
		if ($search->has_terms ())
		{
			// Find the first matched term
			$words = $search->get_terms ();

			foreach ($words AS $word)
			{
				if (preg_match ("/(".$word.")/i", $this->text, $matches, PREG_OFFSET_CAPTURE) > 0)
					$this->first_match = min ($this->first_match, $matches[0][1]);
					
				$this->words[] = $word;
			}
			
			if ($this->first_match == strlen ($this->text))
				$this->first_match = -1;
		}
	}
	
	function strip ($text)
	{
		$text = str_replace ('>', '> ', $text);   // Makes the strip function look better
		$text = wp_filter_nohtml_kses ($text);
		$text = stripslashes ($text);
		$text = preg_replace ('/<!--(.*?)-->/s', '', $text);
		
		return $text;
	}

	
	function zoom ($before = 100, $after = 400)
	{
		$text = $this->text;
		
		// Now zoom
		if ($this->first_match != -1)
		{
			$start = max (0, $this->first_match - $before);
			$end   = min ($this->first_match + $after, strlen ($text));

			$new = substr ($text, $start, $end - $start);
			
			if ($start != 0)
				$new = preg_replace ('/^[^ ]*/', '', $new);
				
			if ($end != strlen ($text))
				$new = preg_replace ('/[^ ]*$/', '', $new);
			
			$new = str_replace (' ,', ',', $new);
			$new = str_replace (' .', '.', $new);

			$new = trim ($new);
			$text = ($start > 0 ? '&hellip; ' : '').$new.($end < strlen ($text) ? ' &hellip;' : '');
		}
		else if ($this->first_match == -1)
		{
			$text = substr ($text, 0, $after);
			$text = preg_replace ('/[^ ]*$/', '', $text);
			$text .= '&hellip;';
		}
		
		$this->text = $text;
	}
	
	function has_matches ()
	{
		return $this->first_match != -1;
	}
	
	function reformat ($text)
	{
		return wpautop ($text);
	}
	
	function get ($reformat = false)
	{
		return $this->text;
	}
	
	function mark_words ($links = false)
	{
		$text = $this->text;
		$html = strpos ($text, '<') === false ? false : true;
		
		$this->mark_links = $links;
		foreach ($this->words AS $pos => $word)
		{
			if ($pos > 5)
				$pos = 1;
		
			$this->word_count = 0;
			$this->word_pos   = $pos;

			if ($html)
				$text = preg_replace_callback ('/(?<=>)([^<]+)?('.$word.')/i', array (&$this, 'highlight_html_word'), $text);
			else
				$text = preg_replace_callback ('/('.$word.')/i', array (&$this, 'highlight_plain_word'), $text);
		}
		
		$this->text = $text;
    return $text;
	}

	function highlight_plain_word ($words)
	{
		$id = '';
		if ($this->word_count == 0 && $this->mark_links)
			$id = 'id="high_'.($this->word_pos + 1).'"';

		$this->word_count++;
		return '<span '.$id.' class="searchterm'.($this->word_pos + 1).'">'.$words[1].'</span>';
	}
	
	function highlight_html_word ($words)
	{
		$id = '';
		if ($this->word_count == 0 && $this->mark_links)
			$id = 'id="high_'.($this->word_pos + 1).'"';
			
		$this->word_count++;
		return $words[1].'<span '.$id.' class="searchterm'.($this->word_pos + 1).'">'.$words[2].'</span>';
	}
}
?>