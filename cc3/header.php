<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
 <head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <? if (is_home() || is_404()) {?>
  <title>Creative Commons</title>
  <? } else { ?>  
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <? }?>
  <meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  
  
  <? if (is_home() || ($category_name == "blog")) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss">
  <? } else if (is_category()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . "/" . $category_name; ?>/feed">
  <? } ?>

  <?php if (is_single() or is_page()) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php } ?>
  
  <style type="text/css">
    @import "<?php bloginfo('stylesheet_directory'); ?>/style.css";
  </style>
  <!--[if IE]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" />
   <style type="text/css">
    img { behavior: url("<?php bloginfo('stylesheet_directory'); ?>/pngie.htc"); }
   </style>	
  <![endif]-->
  
  <?php /* wp_get_archives('type=monthly&format=link'); */ ?>
  <script src="/includes/icommons.js" type="text/javascript"></script>
  <?php wp_head(); ?>
 </head>
 <body onload="">
    <div id="header-wrapper">
      <div id="header-main">
        <a href="<?php echo get_settings('home'); ?>/license/" class="cc-actions"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/publish.png" border="0" class="publish"/> <h4>License</h4>Your Work</a>
        <a href="http://search.creativecommons.org/" class="cc-actions"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/find.png" border="0"/> <h4>Find</h4>CC Licensed Work</a>
        <a href="<?php echo get_settings('home'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/cc-title.png" alt="creative commons" id="cc-title" border="0"/></a>
      </div>
    </div>
    <div id="wrapper"><div id="wrapper-ie">
    
    <div class="jurisdictions">
      <h4><a href="/worldwide">Worldwide</a>&nbsp;</h4>
      <select name="sortby" onchange="orderby(this)">
        <option value="">Select a jurisdiction</option>
        <script type="text/javascript" src="http://api.creativecommons.org/rest/1.5/support/jurisdictions.js"></script>
      </select>
    </div>
    <div class="clear">&nbsp;</div>
    
