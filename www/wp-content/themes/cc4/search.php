<?php get_header(); ?>
    <div id="body">
      <div id="splash">

        <div id="splash-menu">
          
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div class="block" id="title">
        <h2>
          Search Results
        </h2>
          </div>
          <div id="alpha" class="content-box">
           <div class="block hero">
          		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
           </div>
          
<?php if (have_posts())  { ?>
<?php while (have_posts()) { the_post(); ?>

			<div class="block blogged">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
				<small><?php the_time('l, F jS, Y') ?></small>

				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
			</div>


<?php } }?>
            <?php posts_nav_link(' &mdash; ', 'previous page', 'next page'); ?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
