<?php

/*
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

include ('../../../wp-config.php');


class SearchAJAX extends Search_Plugin
{
	function SearchAJAX ()
	{
		if (!current_user_can ('administrator'))
			die ('<p style="color: red">You are not allowed access to this resource</p>');
		
		$command = $_GET['cmd'];
		$_POST = stripslashes_deep ($_POST);
		
		include_once (dirname (__FILE__).'/models/spider.php');
		$this->register_plugin ('headspace', __FILE__);
		if (method_exists ($this, $command))
			$this->$command ();
		else
			die ('<p style="color: red">That function is not defined</p>');
	}
	
	function index ()
	{
		$offset = intval ($_GET['offset']);
		$type   = $_POST['type'];

		global $search_spider;

		// Prevent bad plugins from screwing up the display
//		ob_start ();
		
		// Create a spider
		$spider  = new SearchSpider ($search_spider->get_options ());
		if ($type == 'posts')
		{
			$offset += $spider->index_posts ($offset, 20);
			$total   = $spider->total_posts_to_index ();
		}
		else
		{
			$offset += $spider->index_comments ($offset, 30);
			$total   = $spider->total_comments_to_index ();
		}
		
		$newoffset = $offset;
		if ($offset >= $total)
			$newoffset = 'END';

		$percent = 100;
		if ($total > 0)
			$percent = ($offset / $total) * 100;

		// Capture bad plugin output
		// $output = ob_get_contents ();
		// ob_end_clean ();
		?>
		<div id="inner" style="width: <?php echo $percent ?>%">
			<input type="hidden" name="offset" value="<?php echo $newoffset ?>" id="offset"/>
		</div>
		<p id="status"><?php if ($offset == $total) echo __ ('Finished!  Click to return', 'search-unleashed'); else printf (__ ('%d of %d / %d%%', 'search-unleashed'), $offset, $total, $percent)?></p>
		<?php
	}

	function edit_module ()
	{
		$module = $_POST['id'];
		
		global $search_spider;
		
		$spider = new SearchSpider ($search_spider->get_options ());
		
		$this->render_admin ('module_edit', array ('module' => $spider->get_module ($module)));
	}
	
	function cancel_module ()
	{
		$module = $_POST['id'];
		
		global $search_spider;
		$spider = new SearchSpider ($search_spider->get_options ());
		
		$this->render_admin ('module', array ('module' => $spider->get_module ($module), 'active' => $spider->is_engine_running ($module)));
	}
	
	function save_module ()
	{
		$type = $_POST['id'];
		
		global $search_spider;
		$spider = new SearchSpider ($search_spider->get_options ());
		
		$module = $spider->get_module ($type);
		$search_spider->save_module_options ($type, $module->save ($_POST));
		
		$this->render_admin ('module', array ('module' => $module, 'active' => $spider->is_engine_running ($type)));
	}
	
	function module_on ()
	{
		global $search_spider;
		$search_spider->enable_module ($_POST['module']);
	}
	
	function module_off ()
	{
		global $search_spider;
		$search_spider->disable_module ($_POST['module']);
	}
}


$obj = new SearchAJAX ();

?>