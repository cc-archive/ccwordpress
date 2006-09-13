<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <!--img src="images/info.png" align="left"/-->
        <h2>
        <? if (is_month() || is_year()) { ?> 
          <a href="<?php echo get_settings('home') . "/"?>featured-content/">
            Featured Content
          </a>
        <? } else { ?>
          Featured Content
        <? } ?>
        <br/>&nbsp;</h2>
        <div id="splash-menu">
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
<?php if (have_posts())  { ?>
<?php while (have_posts()) { the_post(); ?>
            <div class="post" id="post-<?php the_ID(); ?>">
              <h4 class="meta"><?php the_time('F jS, Y')?></h4>
              <div class="clearer"></div>
              <? if ($attach = cc_get_attachment ($post->ID)) { ?>
                <a href="<?= $post->post_excerpt ?>"><img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0" align="left" style="margin-bottom: 30px; margin-right: 10px;"/></a>
              <? } ?>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } }?>
            <?php posts_nav_link(' &mdash; ', 'previous page', 'next page'); ?>
          </div>
          <div id="features">
            <h4>Archives</h4>
            <ul class="archives">
              <?php cc_get_cat_archives(2, 'monthly', '', 'html', '', '', TRUE); ?>
            </ul>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
