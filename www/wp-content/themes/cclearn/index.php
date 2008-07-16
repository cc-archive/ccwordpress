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
        <div id="events_map">
	  <iframe frameborder="0" src="/events-map/mvs.html?data=textfile.txt&amp;zoom=1&amp;center=30,0" ></iframe>
	  <div id="events_map_legend">
	   <ul>
	    <li><img src="http://labs.creativecommons.org/~paulproteus/pins/pin_purple_h=20.png" alt="Purple Pin" title="Purple Pin" /> <span>Open Education Event</span></li>
	    <li><img src="http://labs.creativecommons.org/~paulproteus/pins/pin_green_h=20.png" alt="Green Pin" title="Green Pin" /> <span>Open Education Event, CC attending</span></li>
	    <li class="hint">Click a pin for more details on the event</li>
	    <li class="attribution">Pushpin icon by <a href="http://pedrogordo.com">Pedro Gordo</a>, <a href="http://creativ
      ecommons.org/licenses/by/3.0/">CC BY 3.0</a></li>
	  </div>
	  <div class="events_button"><a href="http://wiki.creativecommons.org/Open_Education_Events">View list of all events</a></div>
	  <div class="events_button"><a href="http://wiki.creativecommons.org/Form:Event">Add event</a></div><br clear="all"/> 
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
          <?php cc_build_external_feed("ccLearn Features");?>
        </div>
  	    <div id="beta" class="content-box">
	        <h4>Latest News</h4>
	    
<?php cc_build_external_feed("ccLearn Weblog"); ?>

<?php get_footer(); ?>

