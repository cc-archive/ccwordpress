<?php get_header(); ?>

<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); 
  // check if page should have middle column
  $is_single_col = get_post_meta ($post->ID, "single_col", TRUE); ?>
  
    <div id="body">
      <div id="splash">
         <? if ($post->post_parent) { 
           $parent = cc_page_parent ($post); ?> 
          <h3 class="category">
            <a href="../">
              <?= $parent->post_title ?>
            </a>
          </h3>
          <? }?>
        <h2><?php the_title(); ?><br/>&nbsp;</h2>
        <div id="splash-menu">
          <? /*cc_list_pages($post->ID);*/ ?>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="<?= ($is_single_col) ? 'page' : 'blog' ?>">
            <div class="post" id="post-<?php the_ID(); ?>">
              <?php the_content("Read More..."); ?>
            </div>
<?php } }?>
          </div>
        <? if (!$is_single_col) { ?>
          <div id="features">
            <ul>
          <? cc_list_pages($post->ID, "", "<li>", "</li>"); ?>  
            </ul>
          </div>
        <? } ?>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
