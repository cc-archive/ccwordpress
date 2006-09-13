<?php get_header(); ?>

<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); ?>
  
    <div id="body">
      <div id="splash">
        <h1><?php the_title(); ?><br/>&nbsp;</h1>
        <div id="splash-menu">
          <? cc_list_pages($post->ID); ?>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="page">
            <div class="post" id="post-<?php the_ID(); ?>">
              <?php the_content("Read More..."); ?>
            </div>
<?php } }?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
