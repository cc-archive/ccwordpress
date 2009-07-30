<?php get_header(); ?>

    <div id="body">
      

      

      <div id="content">
     	  <div class="clear">&nbsp;</div>
       <!-- <div id="splash">
		-->	<?php /*echo cc_current_feature(); */?>
		
		<?php include "events-map.php" ?>

		<div id="head-info">
			<div id="mission">
				<p>ccLearn is a division of Creative Commons dedicated to realizing the full potential of the internet to support open learning and open educational resources.</p><p>Our mission is to minimize legal, technical, and social barriers to sharing and reuse of educational materials.</p>
			</div>
			<div id="current">
				<?php echo cc_current_feature(); ?>
			</div>
		</div>
        <!--/div -->
	<div id="projects_box" class="cc_box">
         <!--  <h4>Projects</h4> -->
<?php /* Select all projects and loop through them, displaying their excerpts and images 
       * Projects must be children of the "Projects" page; 
       * must have a 90px wide attached logo; 
       * must have a short excerpt/intro custom field 
   */
  $projects_query = array (
      'post_type' => 'page',
      'post_parent' => cc_id_from_page_name("Projects"),
      'orderby' => 'menu_order',
	  'order' => 'asc',
	  'limit' => '5'
      );
  $projects = get_posts($projects_query);
  
  foreach ($projects as $post) {
    setup_postdata ($post);
    
    // grab logo attachment
    $image = cc_get_attachment ($post->ID);
	$title = str_replace(" ", "_", strtolower($post->post_title));
    ?>
			<div class="floater w20 alt helpLink" id="project_<?php echo $title ?>">
				<h3><a href="<? if (get_post_meta($post->ID, 'redirecturl', true) !='' ) echo get_post_meta($post->ID, 'redirecturl', true); else the_permalink(); ?>"><strong><? the_title() ?></strong></a></h3>
				<a href="<? the_permalink() ?>"><img src="<?= $image->uri ?>" alt="<?= $image->descr ?>" align="left" border="0"/></a>
				<!-- <p><?=  get_post_meta($post->ID, "excerpt", true) ?></p> -->
			</div>
			<div class="popup" id="help_project_<?php echo $title ?>">
				<div class="bd"><?=  get_post_meta($post->ID, "excerpt", true) ?></div>
			</div>
    <?php
  }

?>
        </div>
        <div id="alpha" class="content-box">
		  <h4>Latest News</h4>
      <?php 
      $feed = cc_build_external_feed("ccLearn Weblog"); 

      foreach ($feed as $item) {
        $date = date('F dS, Y', $item['date']);
echo <<<HTML
      <div class='block blogged rss'>
        <div class="rss-title">
          <h3><a href="{$item['link']}">{$item['title']}</a></h3>
          <small class="rss-date">$date</small>
        </div>
        <p>{$item['content']}<br/>[<a href="{$item['link']}">Read More</a>]</p>
      </div>
HTML;
      }
      ?>
        </div>
  	    <div id="beta" class="content-box-right">
	        <h4>Feature</h4>
	    
<?php while(have_posts()) {
		the_post();
		$the_url = get_post_meta($post->ID, "url", true);
?>
		<div class="post">
			<h2><a href="<?php echo $the_url ?>"><?php the_title() ?></a></h2>
<?php the_content(); ?>
			<p><a href="<?php echo $the_url?>">Read More</a></p>
		</div>
<?php } ?>
<?php get_footer(); ?>

