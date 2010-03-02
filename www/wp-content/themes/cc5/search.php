<?php get_header(); ?>

<div id="mainContent" class="box">
  <div id="contentPrimary">
        	<div class="block" id="title">
        <h2>
          <?php _e('Search Results', 'cc5'); ?>
        </h2>
          </div>
          <div  class="content-box" id="page">
           <div class="block hero">
          		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
           </div>
          
<?php if (have_posts())  { ?>
<?php while (have_posts()) { the_post(); ?>
  
    <div class="block blog sideContentSpace" id="post-<?php the_ID(); ?>">
      <h1 class="title">
        <a href="<? the_permalink() ?>">
         <?php if (in_category(4) || in_category(7)) { _e('CC Talks With: ', 'cc5'); } ?> 
         <?php the_title() ?>
        </a>
      </h1>
      <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
      <?php the_excerpt(); ?>
      <?php edit_post_link(__('Edit', 'cc5'), '', ' |'); ?> <?php comments_popup_link(__('No Comments &#187;', 'cc5'), __('1 Comment &#187;', 'cc5'), __('% Comments &#187;', 'cc5')); ?>
    </div>

<?php } ?>

	    <div style="margin: 1ex;">
	    <?php
	    # Add pretty pagination if the plugin PageNavi is installed,
	    # otherwise just use the boring stuff.  nkinkade 2008-01-02
	    if ( function_exists('wp_pagenavi') ) {
	    	wp_pagenavi();
	    } else {
                posts_nav_link(' &mdash; ', __('previous page', 'cc5'), __('next page', 'cc5'));
	    }
	    ?>
	    </div>
<?php } else { ?>
  <h2><?php _e('No search results found.', 'cc5'); ?></h2>

<?php } ?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
