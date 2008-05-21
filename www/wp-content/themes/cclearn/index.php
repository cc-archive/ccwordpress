<?php get_header(); ?>

    <div id="body">
      

      

      <div id="content">
     	  <div class="clear">&nbsp;</div>
        <div id="splash">
          <div class="callout">
            <?php echo cc_current_feature(); ?>
          </div>
        
          <div class="block noclear" id="title">
            <div id="blurb">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/images/apple.png" align="left" border="0" style="border:none;" />
              <?php echo cc_intro_blurb(); ?>
            </div>
          </div>
          <div class="clear"></div>
        </div>
        <div class="cc_box">
          <h4>Projects</h4>
<?php /* Select all projects and loop through them, displaying their excerpts and images 
       * Projects must be children of the "Projects" page; 
       * must have a 90px wide attached logo; 
       * must have a short excerpt/intro custom field 
   */
  $projects_query = array (
      'post_type' => 'page',
      'post_parent' => cc_id_from_page_name("Projects"),
      'orderby' => 'ID',
      'order' => 'asc'
      );
  $projects = get_posts($projects_query);
  
  foreach ($projects as $post) {
    setup_postdata ($post);
    
    // grab logo attachment
    $image = cc_get_attachment ($post->ID);
    
    ?>
          <div class="floater w30 alt">
            <a href="<? the_permalink() ?>"><img src="<?= $image->uri ?>" alt="<?= $image->descr ?>" align="left" border="0"/></a>
            <p style="margin-left: 100px;">
              <a href="<? the_permalink() ?>"><strong><? the_title() ?></strong></a><br/>
              <?= get_post_meta($post->ID, "excerpt", true) ?>
            </p>
          </div>
    <?php
  }
   
?>
          <br clear="both"/>
        </div>
        <div id="alpha" class="content-box">
          <?php cc_build_external_feed("ccLearn Features", false, false, 5, 0);?>
        </div>
  	    <div id="beta" class="content-box">
	        <h4>Latest News</h4>
	    
<?php cc_build_external_feed("CC Education Weblog", true, false); ?>

<?php get_footer(); ?>

