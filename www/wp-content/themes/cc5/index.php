<?php // handle search stuff first

  if ($_GET['s'] && ($_GET['st'] == site)) {
    // site searchtype redirects to google query
    $query = $_GET['s'];
    header("Location:http://www.google.com/custom?q=" . $query . "&sa=search&cof=GIMP%3Ablack%3BT%3A%23333333%3BLW%3A162%3BALC%3Ared%3BL%3Ahttp%3A%2F%2Fcreativecommons.org%2Fimages%2Flogo_trademark.gif%3BGFNT%3A%2399999%3BLC%3A%235e715e%3BLH%3A40%3BBGC%3Awhite%3BAH%3Aleft%3BVLC%3A%238EA48E%3BS%3Ahttp%3A%2F%2Fcreativecommons.org%2F%3BGALT%3A%23666666%3BAWFID%3Afad503ba397c7a7f%3B&domains=creativecommons.org&sitesearch=creativecommons.org");
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Creative Commons</title>
	
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/container/assets/skins/sam/container.css" /> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/animation/animation-min.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/container/container-min.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/cookie/cookie-min.js"></script> 
  	
	<link href="<?php bloginfo('stylesheet_directory'); ?>/index.css" rel="stylesheet" type="text/css" />
  <!-- <link href="<?php bloginfo('stylesheet_directory'); ?>/support.css?5.1" rel="stylesheet" type="text/css" /> -->
	
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js"></script>
		
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />
	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />
	
	
  <?php wp_head(); ?>
</head>

<body class="yui-skin-sam">
  <div id="globalWrapper">
    <div id="headerWrapper" class="box">
      <div id="headerLogo">
        <h1><a href="/"><span>Creative Commons</span></a></h1>
      </div>
      <div id="headerIntro">
        <h4>Share, Remix, Reuse &mdash; Legally</h4>
        <p>Creative Commons is a nonprofit organization that works to reduce barriers to&nbsp;collaboration. <a href="http://creativecommons.org/about/what-is-cc">Learn More &raquo;</a></p>
      </div>
      <?php require_once "nav.php"; ?>
      <div id="headerSearch">
        <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
          <input type="text" name="s" id="s" size="30" class="inactive" />
          <input type="submit" id="searchsubmit" value="Go" />
        </form>
      </div>
    </div>
    
    <div id="mainContent">
      <div id="topContent" class="box">
        <div id="ccBlurb" >
          <div id="cc_mission">
          <!-- <p>Creative Commons is a <strong>nonprofit</strong> organization dedicated to making it easier for people to share and build upon the work of others, consistent with the rules of copyright.</p> -->
          <h4 class="subTitle"><a href="#">A Shared Culture &raquo;</a></h4>
  		    <!-- <object type="application/x-shockwave-flash" data="http://blip.tv/play/goY6yZQBg9ky.m4v" style="width: 300px; height: 199px; margin-right: 10px;"> 
  		                <param name="movie" value="http://blip.tv/play/goY6yZQBg9ky.m4v"> 
  		                <param name="quality" value="high"> 
  		                <param name="menu"> 
  		                <img src="test.gif" alt="Alternate image for non-flash browsers"> 
  		              </object> -->
  		    <img src="<?php bloginfo('stylesheet_directory'); ?>/images/bliptmp.jpg" style="border-bottom:1px solid #eee;"/>
        </div>
          <div id="splashBox">
          <div id="splash">
            <?php if (have_posts()) { 
              the_post(); 
              
              if (is_sticky()) { 
                if ($image = cc_get_attachment_image ($post->ID, 630)) { 
                 
                ?>
            <a href="<?php the_permalink() ?>">
              <img src="<?php echo $image[0] ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="main" />
            </a>
            
            <?php } } } ?>
          </div>
        </div>
        </div>
      </div>

      <div id="triple" class="box">
        <div id="ccContent" class="columnBox">
	      <div id="ccTools" class="columnBox">
	        <div class="stdColumn ccTool helpLink" id="search">
	          <h2 class="find"><a href="http://search.creativecommons.org/">Find &raquo;</a></h2>
	          <p>Find <strong>licensed</strong> works you can share, remix, or&nbsp;reuse.</p>
	        </div>
	        <div class="stdColumn ccTool helpLink " id="license">
	          <h2 class="license"><a href="/license/">License &raquo;</a></h2>
	          <p>Use our <strong>free</strong> tools to inform people how they can reuse and share your creative works.</p>
	        </div>
	        <div class="stdColumn ccTool helpLink lastColumn" id="network">
	          <h2 class="join"><a href="http://support.creativecommons.org/join/">Donate &raquo;</a></h2>
	          <p>Help <strong>support</strong> the&nbsp;Commons.</p>
	        </div>
	      </div>  
      </div>
      </div>
      <div class="box">
        <div id="ccFeatures" class="columnBox">
        <div id="ccINews">
          <div id="latestNews" class="stdColumn">
            <h4 class="titleStrip subTitle">Commons News</h4>
            <ul>
              <?php
              while (have_posts()) {
                the_post();
                
                static $count = 0;
                if ($count == "7") { break; } else {
                  if (!in_category(1) && !is_single()) { continue; }
                  ?>
                  <li>
                    <h5 class="postTitle">
                      <a href="<?php the_permalink() ?>">
                        <?php if (in_category(4) || in_category(7)) { ?>Featured Commoner: <?php } ?>
                        <?php the_title(); ?>
                      </a>
                    </h5>
                    <p><small><?php the_time('F jS, Y')?></small></p>
                  </li>
              <?php
                  $count++;
                }
              } ?>
            </ul>
            <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png"/><a href="<?php echo get_category_link(1);?>"><strong>Read more...</strong></a></p>
          </div>
          <div class="stdColumn">
            <h4 class="titleStrip subTitle">International Community News</h4>
            <ul>
              <?php 
              $inews = cc_build_external_feed('CC Planet', 5, 0); 
              
              foreach ($inews as $item) {
                $date = date('F dS, Y', $item['date']);
echo <<<HTML
              <li>
                <div class="flag">
                  <a href="/international/{$item['country_code']}/">
                    <img src="http://creativecommons.org/images/international/{$item['flag_code']}.png" alt="{$item['flag_code']}" class="country" />
                  </a>
                </div>
                <h5><a href="{$item['link']}">{$item['title']}</a></h5>
                <p><small>$date</small></p>
              </li>
HTML;
              }
              ?>
            </ul>
            <p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png"/><a href="http://planet.creativecommons.org/"><strong>Read more...</strong></a></p>
            
          </div>
        </div>

        <div id="ccLinks">
          <?php /* Saving this for later. (09/04/23)
          <div id="donateBlock">
            <a href="http://support.creativecommons.org/">Help Build the Commons<br/><strong>Support CC</strong></a>
          </div>
          */ ?>
          <div class="infoLinks">
            <h4 class="titleStrip subTitle">Information</h4>
            <ul class="ccContent">
				<?php include "sidelinks.php"; ?>
            </ul>
          </div>
          <div class="infoLinks">
            <h4 class="titleStrip subTitle">Programs</h4>
            <ul>
              <li><a href="http://sciencecommons.org/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/sciencecommonslogo.png" alt="Science Commons" border="0"/></a></li>
              <li><a href="http://learn.creativecommons.org/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/cclearnlogo.png" alt="ccLearn" border="0"/></a></li>
              <li>
				<div id="help_international_list" class="sideitem topright">
				 <div class="bd">
				   <select id="international" name="sortby" onchange="orderby(this)">    
				     <option value="">Select a jurisdiction</option>
				     <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
				   </select>
				 </div>
				</div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- YUI -->
      <div id="help_license">
	    <div class="bd">
	      <p>A CC license marks your work with the freedoms of your choice. This notifies the public how your work can be reused, cutting out the middleman.</p>
	    </div>
	  </div>
	  <div id="help_search">
	    <div class="bd">
	      <p>Search millions of licensed works, and discover content you can use in your next great project.</p>
	    </div>
	  </div>
	  <div id="help_network">
	    <div class="bd">
	      <p>Creative Commons needs your support to help build a participatory culture, in which everyone can actively engage in the creativity that surrounds us.</p>
	    </div>
	  </div>
	  </div>	
    </div>

<?php get_footer(); ?>
