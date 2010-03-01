<?php get_header(); ?>

<div id="mainContent" class="box">
  <div id="contentPrimary">
          <div class="block" id="title"><h2><?php _e('Sorry...', 'cc5'); ?></h2></div>
            <div class="block">
              <p><?php _e('The page you were looking for was not found.', 'cc5'); ?><br/>&nbsp;</p>
              <h4><?php _e('Other Content', 'cc5'); ?></h4>
              <ul class="archives">
                <li><a href="<?php echo get_settings('home'); ?>"><?php _e('Home', 'cc5'); ?></a></li>
                <li><a href="<?php echo get_settings('home'); ?>/weblog/"><?php _e('Weblog', 'cc5'); ?></a></li>
                <li><a href="http://wiki.creativecommons.org"><?php _e('Wiki', 'cc5'); ?></a></li>
                <li><a href="http://search.creativecommons.org"><?php _e('ccSearch', 'cc5'); ?></a></li>
              </ul>
          </div>
        </div>
          
<?php get_sidebar(); ?>
<?php get_footer(); ?>
