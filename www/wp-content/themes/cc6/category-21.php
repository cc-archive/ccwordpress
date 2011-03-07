<?php /* License jurisdiction category page */ ?>
<?php function get_the_jurisdiction($post_id) {
	$cats = get_the_category($post_id);
	$jurisdiction->code = '';
	$jurisdiction->status = '';
	$jurisdiction->name = '';
	foreach ( $cats as $cat ) {
		if ( $cat->category_parent == 21 ) {
			$jurisdiction->code = $cat->category_nicename;
			$jurisdiction->name = $cat->cat_name;
		}
		if ( $cat->cat_ID == 18 ) {
			$jurisdiction->status = 'upcoming';
		} else if ( $cat->cat_ID == 19 ) {
			$jurisdiction->status = 'in-progress';
		} else if ( $cat->cat_ID == 20 ) {
			$jurisdiction->status = 'completed';
		}
	}
	return $jurisdiction;
} 

$is_international = true;

?>

<?php get_header(); ?>

<div id="title" class="container_16">
	<h1 class="grid_16">
		CC Affiliate Network
	</h1>
</div>

<div id="content">
	<div class="container_16">

<?php 

	$affiliate_list = "";
	query_posts("cat=21&orderby=title&order=ASC&posts_per_page=-1");
	if ( have_posts() )  {
		$affiliate_list .= "<div class='icontainer'>\n";

		while ( have_posts() ) {
			the_post();  

			// Grab content for main /international page
			if ( $post->post_name == 'cc-affiliate-network-main-page' ) {
				$main_page_content = $post->post_content;
			}

			$jurisdiction = get_the_jurisdiction($post->ID);

			// Include all posts in category 21 ("Affiliate Network")
			if ( ! $jurisdiction->code ) {
				continue;
			}

			$img = "/images/international/$jurisdiction->code.png";
			$affiliate_list .= "<div class='ifloat'><a href='http://wiki.creativecommons.org/$jurisdiction->name'><img class='flag' border='1' src='$img' alt='$jurisdiction->name' /></a><br /><p><a href='http://wiki.creativecommons.org/$jurisdiction->name'>$jurisdiction->name</a></p></div>\n";
		}
		
		$affiliate_list .= "</div>\n";
	}

	$main_page_content = preg_replace('/\[AFFILIATE LIST\]/', $affiliate_list, $main_page_content);

	echo $main_page_content;

?>

	</div>
</div>

<?php get_footer(); ?>
