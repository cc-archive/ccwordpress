<?php

class Search_Post_Content extends Search_Module
{
	var $before = 100;
	var $after  = 400;
	
	function gather_for_post ($data)
	{
		$content = apply_filters ('the_content', $data->post_content);
		return str_replace(']]>', ']]&gt;', $content);
	}
	
	function highlight ($post, $words, $content)
	{
		$content = apply_filters ('search_unleashed_content', $content, $post);

		$high = new Highlighter ($content, $words, true);
		$high->zoom ($this->before, $this->after);
		$high->mark_words ();
	
		return $high->reformat ($high->get ());
	}
	
	function name () { return __ ('Post content', 'search-unleashed'); }
	
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