<?php
/*
 * Theme Name: 960Base Theme SIMPLE
 * Theme URI: http://960basetheme.kiuz.it
 * Description: Wordpress theme based on 960 Grid System
 * Author: Domenico Monaco
 * Author URI: http://www.kiuz.it
 * Version: 1.0.0 BETA
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<!-- <link rel="shortcut icon" href="favicon.ico"><link rel="icon" type="image/gif" href="animated_favicon1.gif"> -->
	<!-- <meta name="verify-v1" content="XXXXXXXX" /> --> <!-- Google Meta tag for Web Master Tools -->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<?php if (is_home() || is_404()) {?>
  <title>Creative Commons</title>
  <?php } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <?php } else { ?>
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <?php }?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="alternate" type="text/xml" title="Comments RSS 2.0 feed" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.carousel.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js"></script>


	<?php wp_head(); ?>
</head>

<body>

	<div id="top_bar">
		<div id="head-region" class="header-widget">
			<?php	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Region 0') ) : ?><?php endif; ?> 
		</div><!-- END head-widget -->
	</div><!-- END top_bar -->

	<div class="clear">&nbsp;</div>

	<a id="top"></a>
	<div id="header">
		<div class="container_16">
			<div class="grid_16 alpha">
				<h1 id="logo"><a href="<?php echo get_settings('home'); ?>"><span>Creative Commons</span></a></h1>

				<ul class="nav">
					<li><a href="/about/">About</a></li>
					<li><a href="/weblog/">Blog</a></li>
					<li><a href="https://creativecommons.net/donate">Donate</a></li>
					<li><a href="http://wiki.creativecommons.org/FAQ">FAQ</a></li>
					<li><a href="http://wiki.creaticecommons.org/">Wiki</a></li>
					<li><a href="/international/">International</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="page">

