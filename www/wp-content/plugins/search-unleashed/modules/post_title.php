<?php

class Search_Post_Title extends Search_Module
{
	function gather_for_priority ($data)
	{
		return apply_filters ('the_title', $data->post_title, '', '');
	}

	function name () { return __ ('Post title', 'search-unleashed'); }
}

?>