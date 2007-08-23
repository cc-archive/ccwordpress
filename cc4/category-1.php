<?php get_header(); ?>

    <div id="body">
      <div id="content">
        <div id="main-content">
          <div class="post" id="title">
            <? if (is_month() || is_year()) { ?> 
            <h3 class="category">
              <a href="<?php echo get_settings('home') . "/" ?>weblog/">
                weblog
              </a>
            </h3>
            <? }?>
            <h2><? wp_title('') ?></h2>
          </div>
          <div id="alpha" class="content-box">
<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); ?>
            <div class="post" id="post-<?php the_ID(); ?>">
              <h1 class="title"><a href="<? the_permalink() ?>"><?the_title()?></a></h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <div class="clearer"></div>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '',''); ?>
            </div>
<?php } }?>
            <?php posts_nav_link(' &mdash; ', 'previous page', 'next page'); ?>
          </div>
          <div id="beta" class="content-box">
	    <strong><a href="/weblog/rss">Subscribe to RSS</a></strong><br/><br/>
            <h4>Archives</h4>
            <ul class="archives">
            <?php cc_get_cat_archives(1, 'monthly', '', 'html', '', '', TRUE); ?>
            </ul>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
