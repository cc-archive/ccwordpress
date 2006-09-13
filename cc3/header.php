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
    
    #header-wrapper {
      background: url('<?php bloginfo('stylesheet_directory'); ?>/images/header-main-b.png') repeat-x bottom left;
      background-color: #486d00;
    }
    #splash {
    /*  background: no-repeat 125% -20px;
      background-color: #0f2700;*/
    }
    
    #footer {
      background: url('<?php bloginfo('stylesheet_directory'); ?>/images/bg-std.png') repeat-x top left;
    }
    
    a.cc-actions:hover {
      background: url('<?php bloginfo('stylesheet_directory'); ?>/images/header-main-hover-b.png') repeat-x bottom left;
    }
  </style>
  <script src="<?php bloginfo('stylesheet_directory'); ?>/random.js" type="text/javascript" charset="utf-8"></script>
  
  <?php wp_get_archives('type=monthly&format=link'); ?>
  <?php wp_head(); ?>
 </head>
 <body onload="">
    <div id="header-wrapper">
      <div id="header-main">
        <a href="<?php echo get_settings('home'); ?>/publish" class="cc-actions"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/publish.png" border="0" class="publish"/> <h4>Publish</h4></a>
        <a href="http://search.creativecommons.org/" class="cc-actions"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/find.png" border="0"/> <h4>Find</h4></a>
        <a href="<?php echo get_settings('home'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/cc-title.png" alt="creative commons" id="cc-title" border="0"/></a>
      </div>
    </div>
    <div id="wrapper">
    
    <div class="jurisdictions">
      <h4>Worldwide&nbsp;</h4>
      <select name="sortby" onchange="orderby(this)">
        <option value="">Select a jurisdiction</option>
        <option value="http://creativecommons.org/worldwide/ar/">Argentina</option>
        <option value="http://www.creativecommons.at">Austria</option>
        <option value="http://www.creativecommons.org.au">Australia</option>
        <option value="http://creativecommons.org/worldwide/be/">Belgium</option>
        <option value="http://creativecommons.org/worldwide/bg/">Bulgaria</option>
        <option value="http://creativecommons.org/worldwide/br/">Brazil</option>
        <option value="http://creativecommons.ca">Canada</option>
        <option value="http://creativecommons.org/worldwide/ch/">Switzerland</option>
        <option value="http://creativecommons.cl">Chile</option>
        <option value="http://cn.creativecommons.org">Mainland China</option>
        <option value="http://creativecommons.org/worldwide/co/">Colombia</option>
        <option value="http://de.creativecommons.org">Germany</option>
        <option value="http://creativecommons.org/worldwide/dk/">Denmark</option>
        <option value="http://es.creativecommons.org/">Spain</option>
        <option value="http://creativecommons.fi">Finland</option>
        <option value="http://fr.creativecommons.org">France</option>
        <option value="http://creativecommons.org/worldwide/hr/">Croatia</option>
      </select>
    </div>
    <div class="clear">&nbsp;</div>
    