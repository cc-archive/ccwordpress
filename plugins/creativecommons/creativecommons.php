<?php
/* 
Plugin Name: Creative Commons Library
Plugin URI: http://creativecommons.org/
Description: Helper methods to aid CC blogsite.
Version: 1.0
Author: Alex Roberts <alex@creativecommons.org>
Author URI: 
*/


function cc_get_cat_archives($category, $type='', $limit='', $format='html', $before = '', $after = '', $show_post_count = false, $skip = '') {
	global $month, $wpdb;
	
	if ( '' == $type )
		$type = 'monthly';

	if ( '' != $limit ) {
		$limit = (int) $limit;
		if ('' != $skip) {
			$skip .= ',';
		}
		$limit = ' LIMIT '.$skip.$limit;
	}
	// this is what will separate dates on weekly archive links
	$archive_week_separator = '&#8211;';

	// over-ride general date format ? 0 = no: use the date format set in Options, 1 = yes: over-ride
	$archive_date_format_over_ride = 0;

	// options for daily archive (only if you over-ride the general date format)
	$archive_day_date_format = 'Y/m/d';

	// options for weekly archive (only if you over-ride the general date format)
	$archive_week_start_date_format = 'Y/m/d';
	$archive_week_end_date_format	= 'Y/m/d';

	if ( !$archive_date_format_over_ride ) {
		$archive_day_date_format = get_settings('date_format');
		$archive_week_start_date_format = get_settings('date_format');
		$archive_week_end_date_format = get_settings('date_format');
	}

	$add_hours = intval(get_settings('gmt_offset'));
	$add_minutes = intval(60 * (get_settings('gmt_offset') - $add_hours));

	$now = current_time('mysql');

	if ( 'monthly' == $type ) {
		$arcresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) AS posts, category_nicename AS catname FROM $wpdb->posts, $wpdb->post2cat AS p2c, $wpdb->categories as cat WHERE post_date < '$now' AND post_date != '0000-00-00 00:00:00' AND post_status = 'publish' AND p2c.category_id = $category AND cat.cat_ID = p2c.category_id AND p2c.post_id = id GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC" . $limit);
		if ( $arcresults ) {
			$afterafter = $after;
			foreach ( $arcresults as $arcresult ) {
				//$url	= get_month_link($arcresult->year,	$arcresult->month);
				if ($category == 1) {
					$catname = "weblog/archive";
				} else {
					$catname = $arcresult->catname;
				}
				
				$url = get_settings('home') . trailingslashit($daylink) . "$catname/$arcresult->year/$arcresult->month";
				if ( $show_post_count ) {
					$text = sprintf('%s %d', $month[zeroise($arcresult->month,2)], $arcresult->year);
					$after = '&nbsp;('.$arcresult->posts.')' . $afterafter;
				} else {
					$text = sprintf('%s %d', $month[zeroise($arcresult->month,2)], $arcresult->year);
				}
				echo get_archives_link($url, $text, $format, $before, $after);
			}
		}
	} elseif ( 'daily' == $type ) {
		$arcresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, DAYOFMONTH(post_date) AS `dayofmonth` FROM $wpdb->posts, $wpdb->post2cat AS p2c WHERE post_date < '$now' AND post_date != '0000-00-00 00:00:00' AND post_status = 'publish' AND p2c.category_id = $category AND p2c.post_id = id ORDER BY post_date DESC" . $limit);
		if ( $arcresults ) {
			foreach ( $arcresults as $arcresult ) {
				$url	= get_day_link($arcresult->year, $arcresult->month, $arcresult->dayofmonth);
				$date = sprintf("%d-%02d-%02d 00:00:00", $arcresult->year, $arcresult->month, $arcresult->dayofmonth);
				$text = mysql2date($archive_day_date_format, $date);
				echo get_archives_link($url, $text, $format, $before, $after);
			}
		}
	} elseif ( 'weekly' == $type ) {
		$start_of_week = get_settings('start_of_week');
		$arcresults = $wpdb->get_results("SELECT DISTINCT WEEK(post_date, $start_of_week) AS `week`, YEAR(post_date) AS yr, DATE_FORMAT(post_date, '%Y-%m-%d') AS yyyymmdd FROM $wpdb->posts, $wpdb->post2cat AS p2c WHERE post_date < '$now' AND post_status = 'publish' AND p2c.category_id = $category AND p2c.post_id = id ORDER BY post_date DESC" . $limit);
		$arc_w_last = '';
		if ( $arcresults ) {
				foreach ( $arcresults as $arcresult ) {
					if ( $arcresult->week != $arc_w_last ) {
						$arc_year = $arcresult->yr;
						$arc_w_last = $arcresult->week;
						$arc_week = get_weekstartend($arcresult->yyyymmdd, get_settings('start_of_week'));
						$arc_week_start = date_i18n($archive_week_start_date_format, $arc_week['start']);
						$arc_week_end = date_i18n($archive_week_end_date_format, $arc_week['end']);
						$url  = sprintf('%s/%s%sm%s%s%sw%s%d', get_settings('home'), '', '?', '=', $arc_year, '&amp;', '=', $arcresult->week);
						$text = $arc_week_start . $archive_week_separator . $arc_week_end;
						echo get_archives_link($url, $text, $format, $before, $after);
					}
				}
		}
	} elseif ( 'postbypost' == $type ) {
		$arcresults = $wpdb->get_results("SELECT * FROM $wpdb->posts, $wpdb->post2cat AS p2c WHERE post_date < '$now' AND post_status = 'publish' AND p2c.category_id = $category AND p2c.post_id = id ORDER BY post_date DESC" . $limit);
		if ( $arcresults ) {
			foreach ( $arcresults as $arcresult ) {
				if ( $arcresult->post_date != '0000-00-00 00:00:00' ) {
					$url  = get_permalink($arcresult);
					$arc_title = $arcresult->post_title;
					if ( $arc_title )
						$text = strip_tags($arc_title);
					else
						$text = $arcresult->ID;
					echo get_archives_link($url, $text, $format, $before, $after);
				}
			}
		}
	}
}



/* This filter will strip the "/category/" text in a category link */
/* Used primarily for the menu manager plugin */
function cc_fix_category_link($content, $category_id = NULL) {
	return str_replace ("/category/", "/", $content);
}
add_filter ("category_link", "cc_fix_category_link", 10, 2);

/* Don't need "/commoners/" either, unless we like having "/commoners/text/2006/05/foo-writer" */
function cc_fix_menu_links($link, $item) {
	if (strstr($link, "/commoners")) {
		return str_replace ("/commoners/", "/", $link);
	}
	return $link;
}
add_filter ("mm_item_link", "cc_fix_menu_links", 10, 2);

/* Permalink filter to massage our blog urls */
/* Also obscures the "/commoners/" url path that would appaer due to WP's dodgy multiple-category handling code. 
   We now just go to the first category that isn't the "commoenrs" parent, regardless if there is a third cat,
   since there's no real other way to check what the author intended. */
function cc_fix_permalink($content, $post){
	if (strstr($content, "/weblog")) {
	        if (strstr($content, "press-releases")) {
	                return get_settings('home') . "/press-releases/entry/" . $post->ID;
        	}

		return get_settings('home') . "/weblog/entry/" . $post->ID;
  	}
	if (strstr($content, "/commoners/")) {
		$cats = get_the_category($post->ID);
		foreach ($cats as $cat) {
			if($cat->category_nicename != "commoners"){
				return "/" . $cat->category_nicename . "/" . $post->post_name; 
			}
		}
	}
  
	return $content;
} 
add_filter ("post_link", "cc_fix_permalink", 11, 2);

/* Filter for page title modifications */
function cc_page_title($title, $sep) {
	if (strstr($title, "Weblog")) {
		return str_replace ("Weblog", "CC News", $title);
	}
	return $title;
}
add_filter ('wp_title', "cc_page_title", 10, 2);

/* convert category nicename to its respective id value */
/* FIXME: This may actually exist in WP, feel free to replace if it does */
function cc_cat_to_id($category_name) {
	global $wpdb;
	
	return $wpdb->get_var("SELECT cat_ID FROM $wpdb->categories WHERE category_nicename = '$category_name';");
}


/* turns on the use of verbose rewrite output, instead of hacking classes.php */
/* merged from Ryan Borens plugin - http://boren.nu/downloads/rewrite.phps    */
function cc_verbose_rewrite() {
    global $wp_rewrite;

    $wp_rewrite->use_verbose_rules = true;
}

add_action('init', 'cc_verbose_rewrite');

/* Custom rewrite rules to make the blog operate correctly, and appear totally seperate */
function cc_custom_rewrites($wp_rewrite) {
	$rules = array(
		'weblog/entry/([^/]+)' => 'index.php?category_name=blog&p=$1',
		'weblog/archive/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})' => 'index.php?category_name=blog&year=$1&monthnum=$2&paged=$3',
		'weblog/archive/([0-9]{4})/([0-9]{1,2})' => 'index.php?category_name=blog&year=$1&monthnum=$2',
		'weblog/archive/([0-9]{4})/page/?([0-9]{1,})' => 'index.php?category_name=blog&year=$1&paged=$2',
		'weblog/archive/([0-9]{4})' => 'index.php?category_name=blog&year=$1',
		'weblog/rss' => 'index.php?category_name=blog&feed=rss2',
		'weblog([^\s]+)' => 'index.php?category_name=blog'
		);
	
	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter ('generate_rewrite_rules', 'cc_custom_rewrites');

?>
