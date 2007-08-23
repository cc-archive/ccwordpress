<?php get_header(); ?>

    <div id="body">
      <div id="splash">

        <div id="splash-menu">
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div class="post hero" id="title">
<!--              <? /* cc_current_feature(); ?>
            
            <h2><?php bloginfo('description'); ?></h2> */?> -->
            <div id="blurb"><?= cc_intro_blurb() ?></div>
            
          </div>
            <h4>Latest News</h4>
<?php // Get the last 5 posts in the blog category. ?>
<?php // FIXME: perhaps make this configurable in theme settings...? ?>
<?php query_posts('category_name=weblog&showposts=4'); ?>
<?php if (have_posts())  { 
  while (have_posts()) { the_post(); ?>
            <div class="post blogged" id="post-<?php the_ID(); ?>">
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


<?php get_sidebar(); ?>
<?php get_footer(); ?>
