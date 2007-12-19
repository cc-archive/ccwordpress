<?php get_header(); ?>

    <div id="body">
      <div id="splash">

      </div>

      <div id="content">
<? /*        <div id="main-content">*/?>

          		<div class="clear">&nbsp;</div>

          <div class="block" id="title">
<!--              <? /* cc_current_feature(); ?>
            
            <h2><?php bloginfo('description'); ?></h2> */?> -->
            <div id="blurb">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/images/apple.png" align="left" border="0" style="border:none;" />
              <p style="margin-left: 80px">
             <?= cc_intro_blurb() ?>
              </p>
            </div>
          </div>
          <div class="cc_box">
            <h4>Projects</h4>
<? /* Select all projects and loop through them, displaying their excerpts and images 
       * Projects must be children of the "Projects" page; 
       * must have a 90px wide attached logo; 
       * must have a short excerpt/intro custom field 
   */
  $projects_query = array (
      'post_type' => 'page',
      'post_parent' => cc_id_from_page_name("Projects"),
      'orderby' => 'post_name',
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
                  <a href="<? the_permalink() ?>"><strong><? the_title() ?></strong></a>
                  <?= get_post_meta($post->ID, "excerpt", true) ?>
                </p>
              </div>
    <?
  }
   
?>
            <br clear="both"/>
          </div>
          <div id="alpha" class="content-box">
            <div class="block blogged rss">
              <div class="rss-title">
                <h3><a href="http://www.creativecommons.it/node/601">CC Italy: Plug-in CC per OpenOffice.org</a></h3> 
                <small>2007-12-04</small>
              </div>
              <p>
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Fusce aliquet mattis sem. Morbi eleifend, augue eget elementum commodo, augue turpis commodo quam, a egestas erat urna a libero. Nullam ut lectus non mauris aliquam viverra. Duis ullamcorper eros sit amet sem. Vivamus at orci. Sed suscipit. Suspendisse potenti. Fusce eu nisi. Aenean quis arcu ut justo facilisis sodales. Duis luctus risus quis lectus. Integer et mi sed metus faucibus tempor. Donec imperdiet tortor dapibus ipsum. Integer orci purus, feugiat sed, vestibulum in, vulputate sed, arcu. Curabitur quam dui, elementum ut, sodales a, ultricies at, nulla. Pellentesque id dui vel libero sagittis placerat. Phasellus nec mauris sit amet lacus faucibus tincidunt. Vestibulum egestas congue pede. Nulla accumsan. Pellentesque et arcu at velit auctor ullamcorper.
              </p>
            </div>
            
            <div class="block blogged rss">
              <div class="rss-title">
                <h3><a href="http://www.creativecommons.it/node/601">CC Italy: Plug-in CC per OpenOffice.org</a></h3> 
                <small>2007-12-04</small>
              </div>
              <p>
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Fusce aliquet mattis sem. Morbi eleifend, augue eget elementum commodo, augue turpis commodo quam, a egestas erat urna a libero. Nullam ut lectus non mauris aliquam viverra. Duis ullamcorper eros sit amet sem. Vivamus at orci. Sed suscipit. Suspendisse potenti. Fusce eu nisi. Aenean quis arcu ut justo facilisis sodales. Duis luctus risus quis lectus. Integer et mi sed metus faucibus tempor. Donec imperdiet tortor dapibus ipsum. Integer orci purus, feugiat sed, vestibulum in, vulputate sed, arcu. Curabitur quam dui, elementum ut, sodales a, ultricies at, nulla. Pellentesque id dui vel libero sagittis placerat. Phasellus nec mauris sit amet lacus faucibus tincidunt. Vestibulum egestas congue pede. Nulla accumsan. Pellentesque et arcu at velit auctor ullamcorper.
              </p>
            </div>
            
            <div class="block blogged rss">
              <div class="rss-title">
                <h3><a href="http://www.creativecommons.it/node/601">CC Italy: Plug-in CC per OpenOffice.org</a></h3> 
                <small>2007-12-04</small>
              </div>
              <p>
                Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Fusce aliquet mattis sem. Morbi eleifend, augue eget elementum commodo, augue turpis commodo quam, a egestas erat urna a libero. Nullam ut lectus non mauris aliquam viverra. Duis ullamcorper eros sit amet sem. Vivamus at orci. Sed suscipit. Suspendisse potenti. Fusce eu nisi. Aenean quis arcu ut justo facilisis sodales. Duis luctus risus quis lectus. Integer et mi sed metus faucibus tempor. Donec imperdiet tortor dapibus ipsum. Integer orci purus, feugiat sed, vestibulum in, vulputate sed, arcu. Curabitur quam dui, elementum ut, sodales a, ultricies at, nulla. Pellentesque id dui vel libero sagittis placerat. Phasellus nec mauris sit amet lacus faucibus tincidunt. Vestibulum egestas congue pede. Nulla accumsan. Pellentesque et arcu at velit auctor ullamcorper.
              </p>
            </div>
	    </div>
	    <div id="beta" class="content-box">
	    <h4>Latest News</h4>
<?php // Get the latest 5 posts that aren't in the worldwide category. ?>
<?php 
  while (have_posts()) { 
    the_post(); 
    ?>
    
            <div class="block blogged" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<?php the_permalink() ?>">
                  <?php if (in_category(4) || in_category(7)) { ?>Featured Commoner: <?php } ?>
                  <?php the_title(); ?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php }?>
            <ul class="archives">
            <li><h3><a href="/weblog/archive">Weblog Archives</a></h3></li>
	    <li><h3><a href="/weblog/rss">RSS Feed</a></h3></li></ul>
	    </div>
         <?/* </div> */ ?>

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>
