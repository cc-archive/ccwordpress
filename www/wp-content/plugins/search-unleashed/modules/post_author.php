<?php

class Search_Post_Author extends Search_Module
{
	var $link = true;
	
	function gather_for_priority ($data)
	{
		return $data->display_name;
	}
	
	function get_permalink ($id)
	{
		return str_replace (get_bloginfo ('home'), '', get_permalink ($id));	
	}
	
	function get_author ($word, $post)
	{
		if ($this->link === true)
			return sprintf ('<a href="%s">%s</a>', get_author_posts_url ($post->post_author), $word);

		return $word;
	}
	
	function highlight ($post, $words, $content)
	{
		$highlight = new Highlighter (get_author_name ($post->post_author), $words, true);

		if ($highlight->has_matches ())
		{
			$highlight->mark_words ();
			return '<p><strong>'.__ ('Author', 'search-unleashed').':</strong> '.$this->get_author ($highlight->get (), $post).'</p>';
		}
		
		return '';
	}
	
	function name () { return __ ('Post author', 'search-unleashed'); }
	
	function has_config () { return true; }
	
	function load ($config)
	{
		if (isset ($config['link']))
			$this->link = $config['link'];
	}

	function edit ()
	{
		?>
		<tr>
			<th align="right" valign="top"><?php _e ('Display link to author page', 'search-unleashed'); ?>:</th>
			<td>
				<input type="checkbox" name="link"<?php if ($this->link) echo ' checked="checked"' ?>/>
			</td>
		</tr>
		<?php
	}

	function save ($data)
	{
		return array ('link' => isset ($data['link']) ? true : false);
	}
}

?>