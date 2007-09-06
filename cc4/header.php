<?php
/* keep around for future progress emeter usage
// total raised*/
$campaign['total'] = file_get_contents('/home/bse/total.txt');

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
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />
  
  
  <? if (is_home() || ($category_name == "weblog")) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss">
  <? } else if (is_category()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . "/" . $category_name; ?>/feed/rss">
  <? } ?>

  <?php if (is_single() or is_page()) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php } ?>
  
  <link href="<?php bloginfo('stylesheet_directory'); ?>/style.css?4.0" rel="stylesheet" type="text/css" />
  <? if (is_home()) { ?>
  <link href="<?php bloginfo('stylesheet_directory'); ?>/support.css?4.0" rel="stylesheet" type="text/css" />
  <? } ?>

  <!--[if IE]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" /><![endif]-->
  
  <?php /* wp_get_archives('type=monthly&format=link'); */ ?>
  <script src="/includes/icommons.js" type="text/javascript"></script>
  <?php wp_head(); ?>
 </head>
 <body onload="">
  <a name="top"></a>
    <div id="header-wrapper">
      <div id="header-main" class="box">
        <span class="publish">
          <a href="<?php echo get_option('home'); ?>/license/" class="cc-actions">
            <span class="img">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/images/license-8.png" border="0" class="publish"/>
            </span> 
            <span class="option">License</span>Your Work
          </a>
        </span>
        <span class="find">
          <a href="http://search.creativecommons.org/" class="cc-actions">
            <span class="img">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/images/find-8.png" border="0"/>
            </span>
            <span class="option">Find</span>CC Licensed Work
          </a>
        </span>
        
        <span class="logo"><a href="<?php echo get_option('home'); ?>"><span><img src="<?php bloginfo('stylesheet_directory'); ?>/images/cc-title-8.png" alt="creative commons" id="cc-title" border="0"/></span></a></span>
        
<?/*        <div id="strap">Share, Remix, Reuse &mdash; Legally</div> */?>
      </div>
    </div>

    <?php include_once( TEMPLATEPATH . "/nav.php" ); ?>
<? /*
	 <div id="campaign">
	  <div class="box">
	    <div class="title">
	     <a href="http://support.creativecommons.org/"><strong>Annual Fundraising Campaign</strong></a>
        </div>
        
	    <div class="progress" onclick="window.location = 'http://support.creativecommons.org';">
	     <span style="padding-right: <?= $campaign['css'] ?>px;">&nbsp;</span>
		 <div class="results"><a href="http://support.creativecommons.org">$<?= $campaign['matched'] ?> / $50,000</a></div>
	    </div> 
	    
	    
	  </div>
	</div>
*/ ?>
		<div class="clear">&nbsp;</div>
    <div class="box"><div id="wrapper-ie">
    <? /*
    <div class="jurisdictions">
      <h4><a href="/sitemap">Search Site</a>&nbsp;&nbsp;&nbsp;&nbsp;</h4><strong>|</strong>&nbsp;&nbsp;&nbsp;&nbsp;
      <h4><a href="/worldwide">Worldwide</a>&nbsp;</h4>
      <select name="sortby" onchange="orderby(this)">
        <option value="">Select a jurisdiction</option>
        <script type="text/javascript" src="/includes/jurisdictions.js"></script>
      </select>
    </div>
    */ ?>

