<?php

global $wp_db_version;

if ($wp_db_version > 6000)
{
	class Search_Post_Tag extends Search_Module
	{
		var $show = true;
		
		function gather_for_priority ($data)
		{
			$tags = get_object_term_cache ($data->ID, 'post_tag');
			if ( false === $tags )
				$tags = wp_get_object_terms ($data->ID, 'post_tag');

			$tags = apply_filters( 'get_the_tags', $tags );
			if (!empty ($tags))
			{
				foreach ($tags AS $tag)
					$newtags[] = $tag->name;
				
				$tags = implode (', ', $newtags);
			}
			else
				$tags = '';
		
			return $tags;
		}
	
		function highlight ($post, $words, $content)
		{
			if ($this->show)
			{
				$highlight = new Highlighter ($this->gather_for_priority ($post), $words, true);

				if ($highlight->has_matches ())
				{
					$highlight->mark_words ();
			
					return '<p><strong>'.__ ('Tags', 'search-unleashed').':</strong> '.$highlight->get ().'</p>';
				}
			}
		
			return '';
		}
	
		function name ()
		{
			return __ ('Tags', 'search-unleashed');
		}
		
		function has_config () { return true; }
		
		function load ($config)
		{
			if (isset ($config['show']))
				$this->show = $config['show'];
		}

		function edit ()
		{
			?>
			<tr>
				<th align="right" valign="top"><?php _e ('Show tag in results', 'search-unleashed'); ?>:</th>
				<td>
					<input type="checkbox" name="show"<?php if ($this->show) echo ' checked="checked"' ?>/>
				</td>
			</tr>
			<?php
		}

		function save ($data)
		{
			return array ('show' => isset ($data['show']) ? true : false);
		}
	}
}

?>