<?php get_header(); ?>

    <div id="body">
      <div id="content">
        <div id="main-content">
          <div class="block" id="title">
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
            <div class="block" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<? the_permalink() ?>">
                 <?the_title()?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <div class="clearer"></div>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ' |'); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
            </div>
<?php } }?>

            <div style="margin: 1ex;">
            <?php
            # Add pretty pagination if the plugin PageNavi is installed,
            # otherwise just use the boring stuff.  nkinkade 2008-01-02
            if ( function_exists('wp_pagenavi') ) {
                wp_pagenavi();
            } else {
                posts_nav_link(' &mdash; ', 'previous page', 'next page');
            }
            ?>
            </div>

          </div>
        </div>  

<?php get_sidebar(); ?>

<?php get_footer(); ?>
