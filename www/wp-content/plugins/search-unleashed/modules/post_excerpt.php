<?php

class Search_Post_Excerpt extends Search_Module
{
	var $before = 20;
	var $after  = 40;
	
	function gather_for_post ($data)
	{
		return apply_filters ('the_excerpt', $data->post_excerpt);
	}
	
	function highlight ($post, $words, $content)
	{
		// First check if the excerpt is not the same as the content
		$highlight  = new Highlighter ($content, $words, true);
		$highlight2 = new Highlighter ($post->post_content, $words, true);
		
		if (substr ($highlight2->text, 0, strlen ($highlight->text)) != $highlight->text && $highlight->has_matches ())
		{
			$highlight->zoom ($this->before, $this->after);
			$highlight->mark_words ();
			
			return '<p><strong>'.__ ('Excerpt', 'search-unleashed').':</strong> '.$highlight->get ().'</p>';
		}
		
		return '';
	}
	
	function name () { return __ ('Post excerpt', 'search-unleashed'); }
	
	function has_config () { return true; }
	
	function load ($config)
	{
		if (isset ($config['before']))
			$this->before = $config['before'];
			
		if (isset ($config['after']))
			$this->after = $config['after'];
	}
	
	function edit ()
	{
		global $wpdb;
		
		$available = $wpdb->get_results ("SELECT meta_key FROM {$wpdb->postmeta} WHERE meta_key NOT LIKE '%_wp%' GROUP BY meta_key ORDER BY meta_key");
		?>
		<tr>
			<th align="right" valign="top"><?php _e ('Characters before first match', 'search-unleashed'); ?>:</th>
			<td>
				<input type="text" name="before" value="<?php echo $this->before ?>"/>
			</td>
		</tr>
		<tr>
			<th align="right" valign="top"><?php _e ('Characters after first match', 'search-unleashed'); ?>:</th>
			<td>
				<input type="text" name="after" value="<?php echo $this->after ?>"/>
			</td>
		</tr>
		<?php
	}
	
	function save ($data)
	{
		return array ('before' => intval ($data['before']), 'after' => intval ($data['after']));
	}
}

?>