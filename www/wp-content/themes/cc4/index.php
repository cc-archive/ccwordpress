<?php get_header(); ?>

    <div id="body">

      <div id="content">
        <div id="main-content">

	<div id="splash">
	 <div class="splash-box">
		<div class="splash-box-left">
			<a href="http://creativecommons.org/weblog/entry/8051">
				<img src="/images/splash/left-fcw.png" alt="Free Cultural Works" border="0" />
			</a>
			<p class="info">A seal marking CC licenses that qualify as Free Culture Licenses according to the Definition of Free Cultural Works.</p>
		</div>
		<div class="splash-box-right">
			<a href="http://creativecommons.org/weblog/entry/8077">
				<img src="/images/splash/right-pcc.png" alt="Planet Creative Commons" border="0" />
			</a>
                        <p class="info" >Aggregating blogs from Creative Commons, CC jurisdiction projects, and the CC community.</p>
		</div>
	 </div>
	</div>
	<div class="block hero" id="title">
<!--              <? /* cc_current_feature(); ?>
            
            <h2><?php bloginfo('description'); ?></h2> */?> -->
            <div id="blurb"><?= cc_intro_blurb() ?></div>
            
          </div>
          <div id="alpha" class="content-box">
            <a href='/weblog'><h4>CC News</h4></a>
<?php // Get the latest 5 posts that aren't in the worldwide category. ?>
<?php 
  while (have_posts()) { 
    the_post(); 
    
    static $count = 0;
    if ($count == "5") { break; } else {
      if ((in_category(21) && !is_single()) || (in_category(6))) { continue; }?>
    
            <div class="block blogged" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<?php the_permalink() ?>">
                  <?php if (in_category(4) || in_category(7)) { ?>Featured Commoner: <?php } ?>
                  <?php the_title(); ?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php $count++; } }?>
            <ul class="archives">
	    <!-- START nkinkade mods -->
            <!--<li><h3><a href="/weblog/archive">Weblog Archives</a></h3></li>-->
            <li><h3><a href="/weblog">Weblog Archives</a></h3></li>
	    <!-- END nkinkade mods -->
	    <li><h3><a href="/weblog/rss">RSS Feed</a></h3></li></ul>
	    </div>
	    <div id="beta" class="content-box">
	     <a href='http://planet.creativecommons.org/jurisdictions/'><h4>Jurisdiction News</h4></a>
	     <?php cc_build_external_feed(); ?>
	     <div style="margin-left: 1ex;"><a href="http://planet.creativecommons.org/jurisdictions/">[More jurisdiction news]</a></div>
	    </div>
          </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
