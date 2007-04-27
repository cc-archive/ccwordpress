<?php

// total raised
$campaign['total'] = file_get_contents('/web/a2/therm_total/total.txt');

// real total including matched funding
($campaign['total'] < 20000) ? 
	$campaign['matched'] = $campaign['total'] * 2 :
	$campaign['matched'] = $campaign['total'] + 20000;

// figure out value for progress meter
$campaign['css'] = ceil( ($campaign['matched'] / 50000) * 200 );

?>


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
  
  
  <? if (is_home() || ($category_name == "weblog")) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss">
  <? } else if (is_category()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . "/" . $category_name; ?>/feed/rss">
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

 <div style="background-color: #333d33; border-bottom: 1px solid #436400; padding: 5px 38px;  color: #ddd; height: 1.8em; text-align:left;">
   <div style="margin: 0 auto; width: 50em;">
     <div style="float: left; margin-top: 2px;">
	<a style="color: #fff;" href="http://support.creativecommons.org/"><strong>CCi Scholarship Funding Campaign</strong></a>
     </div>

     <div style="cursor:pointer; margin-left: 10px; margin-top: 2px; width: 203px; padding: 0 2px; background-color: #ddd; float: left;  " onclick="window.location = 'http://support.creativecommons.org';">
      <span style="padding-right: <?= $campaign['css'] ?>px; margin: 0; background-color: #a3aaa3;">&nbsp;</span>
     </div> 
    <div style="float: left; margin-top: 2px; margin-left: 7px;"><a style="color:#fff;"  href="http://support.creativecommons.org">$<?= $campaign['matched'] ?> / $50,000</a></div>
   </div>
 </div>

    <div id="wrapper"><div id="wrapper-ie">
    
    <div class="jurisdictions">
      <h4><a href="/sitemap">Search Site</a>&nbsp;&nbsp;&nbsp;&nbsp;</h4><strong>|</strong>&nbsp;&nbsp;&nbsp;&nbsp;
      <h4><a href="/worldwide">Worldwide</a>&nbsp;</h4>
      <select name="sortby" onchange="orderby(this)">
        <option value="">Select a jurisdiction</option>
        <script type="text/javascript" src="/includes/jurisdictions.js"></script>
      </select>
    </div>
    <div class="clear">&nbsp;</div>
    
