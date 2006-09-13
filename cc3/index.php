<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <h2><?php bloginfo('description'); ?><br/>&nbsp;</h2>
        <div id="splash-menu">
          <h3><a href="<?php echo get_settings('home'); ?>/about/">Learn More</a></h3>
          <h3>Support CC</h3>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
            <h4>Latest</h4>
<?php // Get the last 5 posts in the blog category. ?>
<?php // FIXME: perhaps make this configurable in theme settings...? ?>
<?php query_posts('category_name=blog&showposts=5'); ?>
<?php if (have_posts())  { 
  while (have_posts()) { the_post(); ?>
            <div class="post" id="post-<?php the_ID(); ?>">
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
            <h4><a href="weblog/archive/">Archives</a></h4>
            <ul class="archives"><?php cc_get_cat_archives(1, 'postbypost','10','custom','<li>','</li>', FALSE, '5'); ?></ul>
          </div>

          <div id="features">
            <h4>Featured Content</h4>
<?php $my_query = new WP_Query('category_name=featured-content&showposts=3'); ?>
<?php while ($my_query->have_posts()) { $my_query->the_post(); ?>
            <div class="post">
  <?php if ($attach = cc_get_attachment ($post->ID)) { ?>
              <a href="<?= $post->post_excerpt ?>"><img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0" /></a>
  <?php } ?>
              <?php the_content(); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } ?>
            <div class="content-foot"><a href="<?php echo get_settings('home'); ?>/featured-content"><em>More...</em></a></div>

            <h4>Featured Commoners</h4>
<?php $my_query = new WP_Query('category_name=commoners&showposts=3'); ?>
<?php while ($my_query->have_posts()) { $my_query->the_post(); ?>
            <div class="post">
  <?php if ($attach = cc_get_attachment ($post->ID)) { ?>
                <a href="<?php the_permalink() ?>"><img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0"/></a>
  <?php } ?>
              <h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
              <div style="float:left;">
                <em><?php the_time('F Y') ?></em> &mdash;
              </div>
              <?php the_excerpt(); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } ?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
