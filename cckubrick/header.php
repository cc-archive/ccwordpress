<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>
	<?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> 
	<?php wp_title(' '); ?>
	<?php if(wp_title(' ', false)) { echo ' &mdash; '; } ?> 
	<?php bloginfo('name'); ?>
</title>

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if IE]>
   <style type="text/css">
    img { behavior: url("/includes/pngie.htc"); }
   </style>	
  <![endif]-->

<?php wp_head(); ?>
</head>
<body>
<div id="page">


<div id="header">
	<div id="headerimg">
		<a href="<?php echo get_option('home'); ?>/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/cc-logo.png" alt="[ (cc) ]" class="cclogo"/></a>
		<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div>
</div>
<div id="cctools">
  <div class="tool">
    <a href="http://creativecommons.org/license/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/publish.png"/> License Your Work</a>
  </div>
  <div class="tool">  
    <a href="http://search.creativecommons.org/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/find.png"/> Find CC Licensed Work</a>
  </div>
</div>
<hr />
