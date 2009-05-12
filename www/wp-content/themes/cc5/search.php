<?php get_header(); ?>

<div id="mainContent" class="box">
  <div id="contentPrimary">
        	<div class="block" id="title">
        <h2>
          Search Results
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
         <?php if (in_category(4) || in_category(7)) { ?>CC Talks With: <?php } ?> 
         <?php the_title() ?>
        </a>
      </h1>
      <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
      <?php the_excerpt(); ?>
      <?php edit_post_link('Edit', '', ' |'); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
    </div>

<?php } ?>

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
<?php } else { ?>
  <h2>No search results found.</h2>

<?php } ?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
