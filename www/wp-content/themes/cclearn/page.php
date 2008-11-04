<?php get_header(); ?>

<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); 
  // check if page should have middle column
  $is_single_col = get_post_meta ($post->ID, "single_col", TRUE); ?>
  
    <div id="body">
      <div id="content">
          <div class="block page" id="title">
<? if ($post->post_parent) { $parent = cc_page_parent ($post); ?> 
			      <h4 class="category"><a href="./../"><?= $parent->post_title ?></a></h4>
<? }
/*  
if ($parent->post_title == "Projects") {  $image = cc_get_attachment ($post->ID); ?>
			      <img src="<?= $image->uri ?>" alt="<?= $image->descr ?>" align="left" border="0" style="border:none !important; margin-top:5px;"/>
			      <div style="margin-left:110px;">
<? }*/ ?>
			           
			        <h2><?php the_title(); ?></h2>
<?php if ($parent->post_title == "Projects") { ?>
			        <h5><?= get_post_meta($post->ID, "excerpt", true) ?></h5>
			      <!-- /div -->
<? } ?>
          </div>
          <div class="block page" id="post-<?php the_ID(); ?>">
            <?php the_content("Read More..."); ?>
          </div>
<?php } }?>
<?php get_footer(); ?>
