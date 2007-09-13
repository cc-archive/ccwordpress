<?php

/*
Plugin Name: BDP RSS Aggregator Widgets
Plugin URI: http://www.ozpolitics.info/blog/2005/03/28/aggregated-blog-feeds/
Description: Generate sidebar widgets for the RSS Aggregator
Version: 0.0.1
Author: Bryan Palmer (bryan@ozpolitics.info)
Author URI: http://www.ozpolitics.info/blog/

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY 
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHOR(S)
OR COPYRIGHT HOLDER(S) BE LIABLE FOR ANY CLAIM, DAMAGES OR 
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Note: tabs set to four spaces
*/


function widget_bdp_rss_aggregator($args, $number = 1)
{
    extract($args);
	
	$options = get_option('widget_bdp_rss_aggregator');
	
	$title = (isset($options[$number]['title']) && $options[$number]['title']) ? $options[$number]['title'] : __('On the Net');
		
	if(isset($options[$number]['suppress'])) 
		$suppress = ($options[$number]['suppress'] ? TRUE : FALSE);
	else
		$suppress = FALSE;
	
	echo $before_widget; 
	
	echo $before_title . $title . $after_title;
	
	echo '<ul id="widget_bdp_rss_aggregator_ul_'.$number.'">'."\n";
	
	BDPRSS2::output($number);

	if(!$suppress) 
		echo '<li><a href="http://www.ozpolitics.info/blog/2005/03/28/aggregated-blog-feeds/">' . 
			__('About the RSS Aggregator') . '</a></li>';

	echo "</ul>\n";
	
	echo $after_widget; 
}

function widget_bdp_rss_aggregator_control($number)
{
	$options = $newoptions = get_option('widget_bdp_rss_aggregator');
	if ( isset($_POST['bdp-rss-aggregator-submit-'.$number]) ) 
	{
		$newoptions[$number]['title'] = strip_tags(stripslashes($_POST['bdp-rss-aggregator-title-'.$number]));
		$newoptions[$number]['suppress'] = isset($_POST['bdp-rss-aggregator-suppress-'.$number]);
	}
	if ( $options != $newoptions ) 
	{
		$options = $newoptions;
		update_option('widget_bdp_rss_aggregator', $options);
	}

	$title = attribute_escape($options[$number]['title']);
	$suppress = ($options[$number]['suppress'] ? 'checked="checked"' : '');

?>
	<p><label for="bdp-rss-aggregator-title-<?php echo "$number"; ?>"><?php _e('Title:'); ?> <input style="width: 250px;" id="bdp-rss-aggregator-title-<?php echo "$number"; ?>" name="bdp-rss-aggregator-title-<?php echo "$number"; ?>" type="text" value="<?php echo $title; ?>" /></label></p>
	
	<p style="text-align:right;margin-right:40px;"><label for="bdp-rss-aggregator-suppress-<?php echo "$number"; ?>"><?php _e('Suppress link back'); ?> <input class="checkbox" type="checkbox" <?php echo $suppress; ?> id="bdp-rss-aggregator-suppress-<?php echo "$number"; ?>" name="bdp-rss-aggregator-suppress-<?php echo "$number"; ?>" /></label></p>

	<input type="hidden" id="bdp-rss-aggregator-submit-<?php echo "$number"; ?>" name="bdp-rss-aggregator-submit-<?php echo "$number"; ?>" value="1" />
<?php
}

function widget_bdp_rss_aggregator_register() 
{
	$options = get_option('widget_bdp_rss_aggregator');
	$number = (int) $options['number'];
	if ( $number < 1 ) $number = 1;
	if ( $number > 9 ) $number = 9;
	$dims = array('width' => 300, 'height' => 150);
	$class = array('classname' => 'widget_bdp_rss_aggregator');
	for ($i = 1; $i <= 9; $i++) 
	{
		$name = sprintf(__('RSS Aggregator %d'), $i);
		$id = "widget-bdp-rss-aggregator-$i"; // Never never never translate an id
		wp_register_sidebar_widget($id, $name, $i <= $number ? 'widget_bdp_rss_aggregator' : /* unregister */ '', $class, $i);
		wp_register_widget_control($id, $name, $i <= $number ? 'widget_bdp_rss_aggregator_control' : /* unregister */ '', $dims, $i);
	}
}

function widget_bdp_rss_aggregator_page() 
{
	$options = get_option('widget_bdp_rss_aggregator');
?>
	<div class="wrap">
		<form method="POST">
			<h2><?php _e('BDP RSS Aggregator Widgets'); ?></h2>
			<p style="line-height: 30px;"><?php _e('How many RSS Aggregator widgets would you like?'); ?>
			<select id="bdp-rss-aggregator-number" name="bdp-rss-aggregator-number" value="<?php echo $options['number']; ?>">
<?php for ( $i = 1; $i < 10; ++$i ) echo "<option value='$i' ".($options['number']==$i ? "selected='selected'" : '').">$i</option>"; ?>
			</select>
			<span class="submit"><input type="submit" name="bdp-rss-aggregator-number-submit" id="bdp-rss-aggregator-number-submit" value="<?php echo attribute_escape(__('Save')); ?>" /> (Note: Widget numbers correspond with the RSS Aggregator output format IDs)</span></p>
		</form>
	</div>
<?php
}

function widget_bdp_rss_aggregator_setup() 
{
	$options = $newoptions = get_option('widget_bdp_rss_aggregator');
	if ( isset($_POST['bdp-rss-aggregator-number-submit']) ) 
	{
		$number = (int) $_POST['bdp-rss-aggregator-number'];
		if ( $number > 9 ) $number = 9;
		if ( $number < 1 ) $number = 1;
		$newoptions['number'] = $number;
	}
	if ( $options != $newoptions ) 
	{
		$options = $newoptions;
		update_option('widget_bdp_rss_aggregator', $options);
		widget_bdp_rss_aggregator_register();
	}
}

add_action('plugins_loaded', 'widget_bdp_rss_aggregator_register');
add_action('sidebar_admin_page', 'widget_bdp_rss_aggregator_page');
add_action('sidebar_admin_setup', 'widget_bdp_rss_aggregator_setup');

?>
