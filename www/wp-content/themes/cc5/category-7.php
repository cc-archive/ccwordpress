<?php get_header(); 

// Setup category details for template
$category = get_category($cat); ?>

  <div id="mainContent" class="box">
    <div id="contentPrimary">
          	<div class="block" id="title">
			        <? if (is_month() || is_year()) { ?> 
              <h3 class="category">
                <a href="<?php echo get_category_link($cat);?>">
                 <?php echo $category->name; ?> 
                </a>
              </h3>
              <? }?>
              <h2><? wp_title('') ?></h2>
        		</div>
            
            <div id="blocks">            
            <?php if (have_posts())  { ?>
            <?php while (have_posts()) { 
              the_post(); ?>
              <div class="block blog commoner sideContentSpace" id="post-<?php the_ID(); ?>">
                <? if ($attach = cc_get_attachment_image ($post->ID, 150)) { ?>
                  <a href="<?php the_permalink() ?>">
      	            <img align="left" src="<?= $attach[0] ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" class="commoner" border="0" width="150" />
                  </a>
                <? } ?>
                <div class="excerpt">
                  <h1 class="title">
                    <a href="<? the_permalink() ?>">
                     <?php _e('CC Talks With: ', 'cc5'); the_title() ?>
                    </a>
                  </h1>
                  <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
                  <?php the_excerpt(); ?>
                  <?php edit_post_link(__('Edit', 'cc5'), '', ' |'); ?> <?php comments_popup_link(__('No Comments &#187;', 'cc5'), __('1 Comment &#187;', 'cc5'), __('% Comments &#187;', 'cc5')); ?>
                </div>
              </div>
            <?php } }?>
            
            <?php
            # Add pretty pagination if the plugin PageNavi is installed,
            # otherwise just use the boring stuff.  nkinkade 2008-01-02
            if ( function_exists('wp_pagenavi') ) {
                wp_pagenavi();
            } else {
                posts_nav_link(' &mdash; ', __('previous page', 'cc5'), __('next page', 'cc5'));
            }
            ?>
            </div>
            
            <div id="archives">
			<strong><a href="/<?php echo $category->slug; ?>/rss"><?php _e('Subscribe to RSS', 'cc5'); ?></a></strong><br/><br/>
			  <h4><?php _e('Archives', 'cc5'); ?></h4> 
              <ul class="archives">
                <?php cc_get_cat_archives($cat, 'monthly', '', 'html', '', '', TRUE); ?>
              </ul>
            </div>
    </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
