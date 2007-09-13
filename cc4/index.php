<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <? /* Map system goes here */ ?>
        <div id="donormap">
          <iframe
              src="http://labs.creativecommons.org/~paulproteus/mapp.html?data=http://labs.creativecommons.org/~paulproteus/results-icons.txt&zoom=1&center=25,-5"
              id="donormap"
              scrolling="no"
              marginwidth="0" marginheight="0"
              frameborder="0">
          </iframe>
          <div id="mapinfo">
           <h5>2007 Donor Map</h5>
           <p>
            Locations and number of donations to the CC Campaign from around the world.
           </p>
           <p>No information, except your city, is used or shared from this map.</p>
          </div>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
           	<div class="block feature smallpad">
          		<div class="shome first">
          			<ul>
          		   <li><a href="http://ccidonor-dev.civicactions.net/donate"><img src="/images/support/donate.png" border="0"/></a></li>
          		 	<li>Invest in the future of participatory culture<!--. Become a commoner--> by donating to CC today!<br/>&nbsp;</li>	
          		 	</ul>
          		</div>
          		<div class="shome thermo">
            			<a href="http://ccidonor-dev.civicactions.net/"><img src="/images/support/campaign.png" border="0"/></a>
          			<div id="campaign">
	          			<div class="progress" onclick="window.location = 'http://ccidonor-dev.civicactions.net';">
							     <span style="padding-right: 47%;">&nbsp;</span>

							    </div>
								   <div class="results"><a href="http://ccidonor-dev.civicactions.net/">$250,000 / $500,000 by Dec 31</a></div>
							   </div>
						<!--	   <ul><li><strong>Help us meet our goal</strong> of raising $500,000 before December 31st.</li></ul> -->
           		</div>
          		<div class="shome index-last">
          		 <ul>
          		 <li><a href="http://ccidonor-dev.civicactions.net/store"><img src="/images/support/store.png" border="0"/></a></li>
          			<li>Support CC by buying our gear and showing the world around you that you support CC!</li>
          		 </ul>
         		</div>
          		<div class="clear">&nbsp;</div>
            </div>
          <div class="block hero" id="title">
<!--              <? /* cc_current_feature(); ?>
            
            <h2><?php bloginfo('description'); ?></h2> */?> -->
            <div id="blurb"><?= cc_intro_blurb() ?></div>
            
          </div>
          <div id="alpha" class="content-box">
            <h4>Latest News</h4>
<?php // Get the last 5 posts in the blog category. ?>
<?php // FIXME: perhaps make this configurable in theme settings...? ?>
<?php query_posts('category_name=weblog&showposts=4'); ?>
<?php if (have_posts())  { 
  while (have_posts()) { the_post(); ?>
            <div class="block blogged" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<?php the_permalink() ?>">
                  <?php the_title(); ?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } }?>
            <ul class="archives">
            <li><h3><a href="/weblog/archive">Weblog Archives</a></h3></li>
	    <li><h3><a href="/weblog/rss">RSS Feed</a></h3></li></ul>
	    </div>
	    <div id="beta" class="content-box">
	     <h4>Affiliate News</h4>
	     <?php BDPRSS2::output(1); ?>
	    </div>
          </div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>
