<?php get_header(); ?>

<?php if (have_posts())  { ?>
  <?php while (have_posts()) { 
    the_post(); ?>

    <?php
     // check if this single is a commoner or blog post
     in_category(7) ? $is_commoner = true : $is_commoner = false;
     in_category(1) ? $is_blog = true : $is_blog = false;
    ?>
  
    <div id="body">
      <div id="splash">
        <!--img src="images/info.png" align="left"/-->
        <? if ($is_commoner) {?>
          <h3 class="category">
            <a href="<?php echo get_settings('home') . "/" . $category_name; ?>">
              <? $cat = get_the_category(); $cat = $cat[1]; echo $cat->cat_name; ?>
            </a>
          </h3>
        <? } else if ($category_name == "weblog") { ?>
          <h3 class="category">
            <a href="<?php echo get_settings('home') . "/" ?>weblog/">
              weblog
            </a>
          </h3>
        <? } else if ($category_name == "press-releases") { ?>
          <h3 class="category">
            <a href="<?php echo get_settings('home') . "/" ?>press-releases/">
              press-releases 
            </a>
          </h3>
        <? } ?>

        <h2> <?php the_title(); ?><br/>&nbsp;</h2>
        <div id="splash-menu">
         <?php edit_post_link('<h3>Edit this article</h3>', '', ''); ?>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <? if (!$is_commoner) {?>
          <div id="page">
          <? } else { ?>
          <div id="blog">
          <? } ?>
    
            <div class="post" id="post-<?php the_ID(); ?>">
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content(); ?>
              <div class="comments"><?php if ($is_blog) comments_template(); ?></div>
            </div>
          
          <? if ($is_commoner) { ?>
          </div>
          <div id="features">
            <? if ($attach = cc_get_attachment ($post->ID)) { ?>
            <img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0"/><br/>
            <h3><?= the_title() ?></h3>
            <? } ?>
          <? }?>
<?php } }?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
