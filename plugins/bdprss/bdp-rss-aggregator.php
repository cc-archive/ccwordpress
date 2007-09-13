<?php

/*
Plugin Name: BDP RSS Aggregator
Plugin URI: http://www.ozpolitics.info/blog/2005/03/28/aggregated-blog-feeds/
Description: New and Improved RSS Aggregator - collate RSS feeds and summarise to a page - updates regularly without the need for cron.
Version: 0.6.2
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

/* ----- constants ----- */
define ('BDPRSS2_PRODUCT',		'BDP RSS Aggregator');			// Doh!
define ('BDPRSS2_VERSION',		'0.6.1'); 					// CHECK: should be the same as above!
define ('BDPRSS2_DIRECTORY',	'bdprss');						// base in plugins directory

define ('BDPRSS2_DEBUG',		FALSE);

/* ----- initialisation ----- */

if ( !(phpversion() >= '4.3') )
	die( 'Your server is running PHP version ' . phpversion() . 
	' but the BDP RSS Aggregator Wordpress plugin requires at least 4.3' );

if( !function_exists('mb_internal_encoding') || !function_exists('mb_regex_encoding') )
	die( 'Your installation of PHP does not appear to support multibyte strings. '.
	'This support is needed by the BDP RSS Aggregator plugin. '.
	'You should ask your web-host to install it; it is easy to install. '.
	'For more information, refer to <a href="http://www.phpbuilder.com/manual/ref.mbstring.php">'.
	'http://www.phpbuilder.com/manual/ref.mbstring.php</a>.');

/* ----- includes ----- */
require_once(dirname(__FILE__) . '/bdp-rssaggregator-db.php');


/* ----- initialisation ----- */
if(!$bdprss_db->db_exists())
{
	$bdprss_db->create();
	
	/* set up default options */
	update_option('bdprss_update_frequency', 	60 /* minutes */);
	update_option('bdprss_keep_howlong',		0 /* months */);	/* zero = never delete */
}

mb_internal_encoding( get_option('blog_charset') );
mb_regex_encoding( get_option('blog_charset') );

/* ----- main game ----- */
if( !class_exists('BDPRSS2') ) 	// for protection only
{
	/* ----- globals ----- */
	
	add_action('wp_head', array('BDPRSS2', 'tag'));				// advertising
	add_action('admin_menu', array('BDPRSS2', 'adminMenu'));	// link in the relevant admin menus
	add_action('shutdown', array('BDPRSS2', 'updateOldest'));	// routine updating
	add_action('init', array('BDPRSS2', 'rssServe'));			// serve up an RSS feed if necessary
	
	global $bdprssTagSet;
	$bdprssTagSet = array(
		'Links'			=> array('a'),
		'Images'		=> array('img'),
		'Paragraphs' 	=> array('p'),
		'Line breaks' 	=> array('br'),
		'Italics' 		=> array('em', 'i'),
		'Underlining' 	=> array('u'),
		'Bolding' 		=> array('b', 'strong'),
		'Spans' 		=> array('span'),
		'Other text formating'=> array('abbr', 'cite', 'code', 'dfn', 'kbd', 'object', 'pre', 
			'quote', 'ruby', 'samp', 'strike', 'style', 'sub', 'sup', 'var' ),
		'Tables' 		=> array('table', 'tr', 'th', 'td', 'thead', 'tbody', 'tfoot'),
		'Lists' 		=> array('ol', 'ul', 'dl', 'nl', 'li', 'di', 'dd', 'dt', 'label'),
		'Headings' 		=> array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
		'Block quotes' 	=> array('blockquote'),
		'Divisions' 	=> array('div'),
		'Separators' 	=> array('separator', 'hr')
	);
	
	$bdprssdate = 'bdprssarchivedate';
	$bdprssmonth = 'bdprssarchivemonth';
	$bdprssCacheItem = 'bdprsscacheitem';
	$bdprssList = 'bdprsslist';
	$bdprssRSSFeed = 'bdprssfeed';
	
	class BDPRSS2 
	{
		/* --- hooks --- */
		
		function tag() 
		{
		/* tag() 
		 *  -- called early on to place a comment tag in the page 
		 *	-- I use the tag when people ask for help debugging why the plugin doesn't work for them.
		 */
			global $bdprss_db;
			echo "\n\t<!-- ".BDPRSS2_PRODUCT." " .BDPRSS2_VERSION. " -->\n";
			if( $bdprss_db->get_mysql_version() < '4.0' )
				echo "\t<!-- Warning: Your version of MySQL (" . $bdprss_db->get_mysql_version() . 
				") appears old. You should update it. -->\n";
		}
		
		function adminMenu()
		{
		/* adminMenu() -- called when the administration pages are being displayed
		 *	-- this function hooks the RSS Feeds page into the admin menus
		 */
			if (function_exists('add_management_page')) 
				add_management_page('BDP RSS Aggregator', 'RSS Feeds', 9, 
					dirname(__FILE__).'/bdp-rssadmin.php');
		}
		
		function updateOldest() 
		{
		/* updateOldest() -- called at shutdown 
		 *  -- calls $bdprss_db->updateoldest() to find the most out of date feed
		 *	-- $bdprss_db->updateoldest() then calls BDPRSS2::update() to do the actual updating.
		 */
			global $bdprss_db;
			$bdprss_db->updateOldest();
		}
		
		
		/* --- core input functions --- */
		
		function update(&$row) 
		{
			if( !class_exists('Snoopy') ) require_once(ABSPATH."wp-includes/class-snoopy.php");
			require_once(dirname(__FILE__) . '/bdp-rss-update.php');
			require_once(dirname(__FILE__) . '/bdp-rssfeed.php');
			BDPRSSUPDATE::update($row);
		}
		
		
		/* --- core output functions --- */
		
		function output($list_id) 
		{
			require_once(dirname(__FILE__) . '/bdp-rss-output.php');
			BDPRSSOUTPUT::output($list_id);
		}
		
		function archiveList($listID=1, $mode='day', $period=31, $format='d M Y')
		{
			require_once(dirname(__FILE__) . '/bdp-rss-output.php');
			BDPRSSOUTPUT::archiveList($listID, $mode, $period, $format);
		}
		
		function viewCache()
		{
			require_once(dirname(__FILE__) . '/bdp-rss-output.php');
			BDPRSSOUTPUT::viewCache();
		}
		
		function archiveDate($list_id, $dateFormat='d M Y')
		{
			require_once(dirname(__FILE__) . '/bdp-rss-output.php');
			BDPRSSOUTPUT::archiveDate($list_id, $dateFormat);
		}
		
		function rssLink($listID=1, $linkText='RSS feed for this page')
		{
			global $bdprss_db, $bdprssRSSFeed;
			
			$listInfo = $bdprss_db->get_list($listID);
			if(!$listInfo) return;
			
			if($listInfo->{$bdprss_db->lcanrss} != 'Y') return;
			
			echo "<a href='".get_option('siteurl')."?$bdprssRSSFeed=$listID'>$linkText</a>";
		}
		
		function rssServe()
		{
			global $bdprss_db, $bdprssRSSFeed, $bdprssFeedMulti;
			
			$listIDS = intval($_GET[$bdprssRSSFeed]);
			if(!$listIDS) return;
			
			$listInfo = $bdprss_db->get_list($listIDS);
			if(!$listInfo) return;
			
			if($listInfo->{$bdprss_db->lcanrss} != 'Y') return;
			if(!$listInfo->{$bdprss_db->lrsslink}) return;
			if(intval($listInfo->{$bdprss_db->lrssnumbersingle}) <= 0) return;
			
			require_once(dirname(__FILE__) . '/bdp-rss-output.php');
			
			BDPRSSOUTPUT::rssServe($listInfo);
			
			exit;
		}
		
		
		/* ----- utilities ----- */
		
		function tagalise($key)
		{
			return 'bdprss_xhtml_' . preg_replace("'[\s]*'", '', strtolower($key));
		}
		
		function getage($seconds) 
		{
			if($seconds < 1000000) return "never";		// usually true :)
			
			$age = (time() - $seconds);
			
			if($age < 0) 
			{
				$future = TRUE;
				$age = -$age;
			}
			
			$unit = 'seconds';
			
			if($age>120.0) 
			{				
				$age /= 60;
				$unit = 'minutes';
			}	
			
			if($age>120.0 && $unit=='minutes') 
			{				
				$age /= 60;
				$unit = 'hours';
			}	

			if($age>48.0 && $unit=='hours') 
			{				
				$age /= 24;
				$unit = 'days';
			}	
			
			if($age>21.0 && $unit=='days') 
			{				
				$age /= 7;
				$unit = 'weeks';
			}
			
			if($age>13.0 && $unit=='weeks') 
			{				
				$age /= 4.34821;
				$unit = 'months';
			}
			
			if($age>=24.0 && $unit=='months') 
			{				
				$age /= 12;
				$unit = 'years';
			}
				
			$age = round($age, 0);
			if(!$future) return "$age $unit ago";
			return "in $age $unit";
		}
		
		
	} // class BDPRSS2 
} // if( !class_exists('BDPRSS2') )
?>