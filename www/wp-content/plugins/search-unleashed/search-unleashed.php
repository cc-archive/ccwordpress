<?php
/*
Plugin Name: Search Unleashed
Plugin URI: http://urbangiraffe.com/plugins/search-unleashed/
Description: Advanced search engine that provides full text searching across posts, pages, comments, titles, and URLs.  Searches take into account any data added by other plugins, and all search results are contextually highlighted. You can also highlight incoming searches from popular search engines.
Version: 0.2.6
Author: John Godley
Author URI: http://urbangiraffe.com/
============================================================================================================

0.1    - Initial release
0.1.1  - Fix bugs, add option to highlight titles in other themes, add option to prioritize title and URL
0.1.2  - Fix clash with Social Bookmarking plugin, allow one character searches
0.1.3  - Fix problem with posts without content (i.e. attachments)
0.1.4  - Stop search on admin interface
0.1.5  - Add 'force' option for themes that don't display search results
0.1.6  - Database compatability, fix for Related Posts plugin
0.1.7  - Fix for when search term reduces to nothing
0.1.8  - Re-allow small words
0.1.9  - Add tag name searching for WP 2.3
0.1.10 - Make recent posts widget work, better post update notification
0.1.11 - Add option to search protected posts
0.1.12 - Support for Nice Search
0.1.13 - Added Swedish translation, re-arrange options, add post-title option
0.1.14 - Force UTF-8 database to enable accented characters
0.2.0  - Add search log, add search mode
0.2.1  - Add Russian translation
0.2.2  - Theme compatibility, add Yandex search engine, prevent empty search results
0.2.3  - Ticket #3
0.2.4  - Tag option to disable output, remove small words from incoming searches, add 'post author' module,
         restyle incoming search, improve compatability with other plugins
0.2.5  - Fix database problems, add Italian translation, remove site: from Google
0.2.6  - Fix problem with XMLRPC posting not being captured

============================================================================================================
This software is provided "as is" and any express or implied warranties, including, but not limited to, the
implied warranties of merchantibility and fitness for a particular purpose are disclaimed. In no event shall
the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or
consequential damages (including, but not limited to, procurement of substitute goods or services; loss of
use, data, or profits; or business interruption) however caused and on any theory of liability, whether in
contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of
this software, even if advised of the possibility of such damage.

For full license details see license.txt
============================================================================================================ */

include (dirname (__FILE__).'/plugin.php');
include (dirname (__FILE__).'/models/spider.php');
include (dirname (__FILE__).'/models/search_parser.php');
include_once (dirname (__FILE__).'/models/search_engine.php');

define ('SU_DB_VERSION', 2);

$SU_no_page_index = true;

class SearchUnleashedPlugin extends Search_Plugin
{
	var $in_search = false;
	var $fields = null;
	var $limits = null;
	var $spider;
	var $incoming = null;
	var $show_kitten = false;
	var $searched = false;
	var $has_loop_started = false;
	var $has_loop_ended   = false;
	
	var $last_post_id    = null;
	var $last_post_count = 0;
	
	function SearchUnleashedPlugin ()
	{
		$this->register_plugin ('search-unleashed', __FILE__);

		if (is_admin ())
		{
			$this->add_action ('admin_menu');
			$this->add_filter ('admin_head');	
			
			$this->register_activation (__FILE__);
			$this->register_deactivation (__FILE__);
		}
		
		$this->add_action ('save_post');
		$this->add_action ('delete_post');

		$this->add_filter ('posts_fields');
		$this->add_filter ('post_limits');
		$this->add_filter ('posts_request');
		$this->add_filter ('wp_set_comment_status', 'wp_set_comment_status', 10, 2);
		$this->add_filter ('comment_post', 'comment_post', 10, 2);
		$this->add_filter ('edit_comment');
		$this->add_action ('loop_start');
		$this->add_action ('loop_end');
		
		$this->add_action ('wp_head', 'incoming');
	}
	
	function database_upgrade ()
	{
		$version = get_option ('search_unleashed_version');
		if ($version !== SU_DB_VERSION)
		{
			include_once (dirname (__FILE__).'/models/database.php');
		
			$db = new SU_Database ();
			$db->upgrade ($version, SU_DB_VERSION);
		}
	}
	
	function activation ()
	{
		include_once (dirname (__FILE__).'/models/database.php');
		
		$db = new SU_Database ();
		$db->install ();
	}
	
	function deactivate ()
	{
		include_once (dirname (__FILE__).'/models/database.php');
		
		$db = new SU_Database ();
		$db->remove ();
	}
	
	function edit_comment ($commentid)
	{
		$comment = get_comment ($commentid);

		if ($comment->comment_approved == 1)
		{
			$spider = new SearchSpider ($this->get_options ());
			$spider->comment_to_search ($comment, true);
		}
	}
	
	// We do this complicated pending queue because other plugins (Spam Karma) completley change the comment filter process
	// meaning that we never get to know about new approved comments
	function comment_post ($id, $status)
	{
		$pending = get_option ('search_unleashed_pending');
		$pending[] = $id;
		
		update_option ('search_unleashed_pending', $pending);
	}
	
	function wp_set_comment_status ($id, $status = '')
	{
		$spider = new SearchSpider ($this->get_options ());
		$comment = get_comment ($id);
		
		if ($comment->comment_approved == 'approve' || $comment->comment_approved == 1)
			$spider->comment_to_search ($comment, true);
		else
			$spider->remove_comment ($id);
	}
	
	function incoming ()
	{
		$options        = $this->get_options ();
		$this->incoming = new Search_Engine ($_SERVER['HTTP_REFERER']);

		if ((is_single () || is_page ()) && $this->incoming->is_incoming_search () && $options['incoming'] != 'none')
		{
			include (dirname (__FILE__).'/models/highlighter.php');
			include (dirname (__FILE__).'/models/search_log.php');

			$log = new Search_Log ();
			$log->record ($this->incoming->search, $this->incoming);
			
			$this->wp_head ();

			$this->add_filter ('the_content',        'highlight_incoming');
			$this->add_filter ('the_excerpt',        'highlight_incoming');
			
			if ($options['incoming'] == 'all')
				$this->add_filter ('the_title', 'highlight_incoming_title');
				
			$this->add_filter ('get_comment_text',   'highlight_incoming_text');
			$this->add_filter ('get_comment_author', 'highlight_incoming_text');
			
			global $wp_db_version;

			if ($wp_db_version > 6000)
				$this->add_filter ('the_tags', 'highlight_incoming_text');
		}
		
		$pending = get_option ('search_unleashed_pending');
		if ($pending !== false && !empty ($pending))
		{
			$spider = new SearchSpider ($options);
			
			delete_option ('search_unleashed_pending');

			foreach ($pending AS $id)
			{
				$comment = get_comment ($id);
				if ($comment->comment_approved == 1)
					$spider->comment_to_search ($comment, true);
			}
		}
	}

	function highlight_incoming_text ($title)
	{
		if (in_the_loop ())
		{
			$parser = new SearchParser ($this->incoming->get_search ());
			$highlight = new Highlighter ($title, $parser);
	
			return $highlight->mark_words (true);
		}
		
		return $title;
	}
	
	function highlight_incoming ($text)
	{
		if (in_the_loop () && !$this->has_loop_ended)
		{
			$parser = new SearchParser ($this->incoming->get_search ());
			$highlight = new Highlighter ($text, $parser);

			if (is_single () && $this->has_loop_started)
				$this->has_loop_ended = true;

			$text = $this->capture ('incoming_local', array ('words' => $parser->get_terms_for_display (), 'engine' => $this->incoming)).$highlight->mark_words (true);
			return $text;
		}
		
		return $text;
	}
	
	function save_post ($id)
	{
		$spider = new SearchSpider ($this->get_options ());
		$spider->post_to_search (get_post ($id), true);
	}
	
	function delete_post ($id)
	{
		$spider = new SearchSpider ($this->get_options ());
		$spider->remove_post ($id);
	}
	
	function posts_fields ($fields)
	{
		$this->fields = $fields;
		return $fields;
	}
	
	function post_limits ($limits)
	{
		$this->limits = $limits;
		return $limits;
	}
	
	function posts_request ($request)
	{
		if (is_search () && !is_admin () && !$this->searched)
		{
			include_once (dirname (__FILE__).'/models/highlighter.php');
			include (dirname (__FILE__).'/models/search_log.php');

			$options = $this->get_options ();
			
			$options['count']++;
			$this->searched = true;
			update_option ('search_unleashed', $options);

			$this->spider = new SearchSpider ($options);
			$request = $this->spider->get_search_sql (get_query_var ('s'), $this->limits, $this->fields, $request, $options);

			if ($request)
			{
				$log = new Search_Log ();
				$log->record (get_query_var ('s'));

				$this->add_filter ('the_content', 'the_content', 15);
				$this->add_filter ('the_excerpt', 'the_excerpt', 15);
				$this->add_filter ('the_title');
			
				if ($options['kitten'] == false)
					$this->show_kitten = true;
			
				if ($options['page_title'])
					$this->add_filter ('wp_title', 'wp_title', 10, 2);
				$this->add_action ('wp_head');
			
				// If we are in the default theme then force it to display post content
				if ($options['force_display'] == true || get_option ('template') == 'default')
					$this->add_action ('the_time');
			}
		}
		
		return $request;
	}
	
	function wp_title ($title, $sep)
	{
		global $wp_query;

		if ($wp_query->max_num_pages > 1)
		{
			$paged = get_query_var ('paged');
			$max   = $wp_query->max_num_pages;
			
			if ($paged == 0)
				$paged = 1;
			$pre = sprintf (' (page %d of %d)', $paged, $max);
		}
		
		return sprintf (__ ('Search results for \'%s\'', 'search-unleashed'), htmlspecialchars (get_query_var ('s'))).$pre;
	}
	
	function wp_head ()
	{
		$options = $this->get_options ();
		if ($options['include_css'])
			$this->render_admin ('css', array ('highlight' => $options['highlight']));
	}
	
	function the_time ($time)
	{
		if (in_the_loop ())
		{
			global $post;
			return $time.'</small>'.$this->the_content ($post->post_content).'<small>';
		}
		return $time;
	}
	
	function highlight_incoming_title ($title)
	{
		// Only if loop_start has been sent
		if (in_the_loop ())
		{
			global $post;
		
			if ($this->last_post_id != $post->ID)
			{
				$this->last_post_id    = $post->ID;
				$this->last_post_count = 0;
			}

			$options = $this->get_options ();
		
			$this->last_post_count++;
			if ($this->last_post_count - 1 != $options['theme_title_position'])
				return $title;
		
			$parser = new SearchParser ($this->incoming->get_search ());
			$highlight = new Highlighter ($title, $parser);
	
			return $highlight->mark_words (true);
		}
		
		return $title;
	}
	
	function loop_start ()
	{
		$this->has_loop_started = true;
	}
	
	function loop_end ()
	{
		$this->has_loop_ended = true;
	}
	
	function the_title ($text)
	{
		// Only if loop_start has been sent
		if (in_the_loop ())
		{
			global $post;
		
			if ($this->last_post_id != $post->ID)
			{
				$this->last_post_id    = $post->ID;
				$this->last_post_count = 0;
			}

			$options = $this->get_options ();
		
			$this->last_post_count++;
			if ($this->last_post_count - 1 != $options['theme_title_position'])
				return $text;
		
			if ($this->spider->is_engine_running ('search_post_title'))
			{
				$high = new Highlighter ($text, $this->spider->search_terms);
				$high->mark_words ();
			
				return $high->get ();
			}
		}
		return $text;
	}
	
	function the_content ($text)
	{
		if (in_the_loop ())
		{
			global $post;
			if (!$this->in_search)
				$text = $this->spider->highlight_all ($post, $text);
			
			if ($this->show_kitten)
				$pre = $this->capture_admin ('kitten');

			$this->show_kitten = false;
		}
		return $pre.$text;
	}
	
	function the_excerpt ($text)
	{
		if (in_the_loop ())
		{
			// Replace the excerpt with the content
			global $post;

			$this->in_search = true;
			if ($this->show_kitten)
				$pre = $this->capture_admin ('kitten');
			
			$this->show_kitten = false;
			return $pre.$this->spider->highlight_all ($post, apply_filters ('the_content', $post->post_content));
		}
		
		return $text;
	}

	/**
	 * Insert CSS and JS into administration page
	 *
	 * @return void
	 **/
	
	function admin_head ()
	{
		if (strpos ($_SERVER['REQUEST_URI'], 'search-unleashed.php') !== false)
			$this->render_admin ('head');
	}
	
	
	/**
	 * Add HeadSpace menu
	 *
	 * @return void
	 **/
	
	function admin_menu ()
	{
		$this->database_upgrade ();
		
    add_management_page (__ ("Search Unleashed", 'search-unleashed'), __ ("Search Unleashed", 'search-unleashed'), "administrator", basename (__FILE__), array ($this, "admin_spider"));
	}

	function admin_spider ()
	{
		if (current_user_can ('administrator'))
		{
			$sub = isset ($_GET['sub']) ? $_GET['sub'] : '';
		  $url = explode ('&', $_SERVER['REQUEST_URI']);
		  $url = $url[0];
			$this->render_admin ('submenu', array ('url' => $url, 'sub' => $sub));

			if ($sub == '')
				$this->admin_index ();
			else if ($sub == 'log')
				$this->admin_log ();
			else if ($sub == 'options')
				$this->admin_options ();
			else if ($sub == 'modules')
				$this->render_admin ('modules', array ('types' => SearchSpider::available (), 'options' => $this->get_options ()));
		}
		else
			$this->render_message ('You are not allowed access to this resource');
	}
	
	function admin_log ()
	{
		include (dirname (__FILE__).'/models/search_log.php');
		include (dirname (__FILE__).'/models/pager.php');
		
		if (isset ($_POST['delete']))
		{
			Search_Log::delete_all ();
			$this->render_message (__ ('All logs have been deleted.', 'search-unleashed'));
		}
		
		$pager = new Search_Pager ($_GET, $_SERVER['REQUEST_URI'], 'searched_at', 'DESC');
		
		$this->render_admin ('log', array ('logs' => Search_Log::get ($pager), 'pager' => $pager));
	}

	function admin_options ()
	{
		if (isset ($_POST['save']))
		{
			$options = $this->get_options ();

			$options['kitten']        = isset ($_POST['kitten']) ? true : false;
			$options['include_css']   = isset ($_POST['include_css']) ? true : false;
			$options['incoming']      = $_POST['incoming'];
			$options['search_mode']   = $_POST['search_mode'];
			$options['page_title']    = isset ($_POST['page_title']) ? true : false;
			$options['force_display'] = isset ($_POST['force_display']) ? true : false;
			$options['protected']     = isset ($_POST['protected']) ? true : false;
			$options['private']       = isset ($_POST['private']) ? true : false;
			$options['draft']         = isset ($_POST['draft']) ? true : false;
			$options['highlight']     = array_map (array (&$this, 'highlight_code'), $_POST['highlight']);
			$options['exclude']       = trim (preg_replace ('/,{2,}/', ',', preg_replace ('/[^0-9,]/', '', $_POST['exclude'])), ',');

			$options['theme_title_position']  = intval ($_POST['theme_title_position']);
			
			update_option ('search_unleashed', $options);
			$this->render_message (__ ('Your options have been saved', 'search-unleashed'));
		}
		
		$this->render_admin ('options', array ('options' => $this->get_options ()));
	}

	function highlight_code ($code)
	{
		$code = preg_replace ('/[^0-9A-Fa-f]/', '', $code);
		
		if (strlen ($code) < 6 && $code != '')
			$code .= str_repeat ('0', 6 - strlen ($code));
		else if (strlen ($code) > 6)
			$code = substr ($code, 0, 6);
			
		return strtoupper ($code);
	}
	
	function admin_index ()
	{
		$options = $this->get_options ();
		$spider = new SearchSpider ($options);
		
		$total = $spider->total_indexed ();
		$last  = get_option ('search_unleashed_last');
		
		$this->render_admin ('index', array ('total' => $total, 'options' => $options));
	}
	
	function get_options ()
	{
		$options = get_option ('search_unleashed');
		if ($options === false)
			$options = array ();
			
		if (!isset ($options['highlight'][0]) || $options['highlight'][0] == '')
			$options['highlight'][0] = 'FFFF00';
			
		if (!isset ($options['highlight'][1]) || $options['highlight'][1] == '')
			$options['highlight'][1] = 'F7B34F';
			
		if (!isset ($options['highlight'][2]) || $options['highlight'][2] == '')
			$options['highlight'][2] = 'A0F74F';
			
		if (!isset ($options['highlight'][3]) || $options['highlight'][3] == '')
			$options['highlight'][3] = '4FCFF7';
			
		if (!isset ($options['highlight'][4]) || $options['highlight'][4] == '')
			$options['highlight'][4] = 'F7C7F1';

		$defaults = array
		(
			'force_display'        => false,
			'count'                => 0,
			'theme_title_position' => 1,
			'protected'            => false,
			'draft'                => false,
			'private'              => false,
			'kitten'               => false,
			'search_mode'          => 'like',
			'incoming'             => 'all',
			'page_title'           => true,
			'include_css'          => true,
			'active'               => array ('search_post_content' => 'search_post_content', 'search_post_title' => 'search_post_title', 'search_post_excerpt' => 'search_post_excerpt')
		);
		
		foreach ($defaults AS $name => $value)
		{
			if (!isset ($options[$name]))
				$options[$name] = $value;
		}
		
		return $options;
	}
	
	function enable_module ($module)
	{
		$options = $this->get_options ();
		$options['active'][$module] = $module;
		
		update_option ('search_unleashed', $options);
	}
	
	function disable_module ($module)
	{
		$options = $this->get_options ();
		unset ($options['active'][$module]);
		
		update_option ('search_unleashed', $options);
	}
	
	function save_module_options ($module, $settings)
	{
		$options = $this->get_options ();
		
		$options['modules'][$module] = $settings;
		
		update_option ('search_unleashed', $options);
	}
}


/**
 * Instantiate the plugin
 *
 * @global
 **/

$search_spider = new SearchUnleashedPlugin;


?>
