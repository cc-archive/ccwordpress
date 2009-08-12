<?php get_header(); ?>

    <div id="body">

      <div id="content">
        <div id="main-content">
<?php /* removing splash featured things for now - ar, 2008-04-25 
	<div id="splash">
	 <div class="splash-box">
		<div class="splash-box-left">
			<a href="http://creativecommons.org/weblog/entry/8051">
				<img src="/images/splash/left-fcw.png" alt="Free Cultural Works" border="0" />
			</a>
			<p class="info">A seal marking CC licenses that qualify as Free Culture Licenses according to the Definition of Free Cultural Works.</p>
		</div>
		<div class="splash-box-right">
			<a href="http://creativecommons.org/weblog/entry/8091">
				<img src="/images/splash/right-pcc.png" alt="Planet Creative Commons" border="0" />
			</a>
                        <p class="info" >Aggregating blogs from Creative Commons, CC jurisdiction projects, and the CC community.</p>
		</div>
	 </div>
	</div>
*/ ?>
	
	<!--
	<div class="block hero" id="title">
<!--              <? /* cc_current_feature(); ?>
            
            <h2><?php bloginfo('description'); ?></h2> */?> -->
            <div id="blurb"><?= cc_intro_blurb() ?></div>
            
          </div>
	  -->
          <div id="alpha" class="content-box">
            <a href='/weblog'><h4>Labs News</h4></a>
<?php 
  while (have_posts()) { 
    the_post(); 
?>
            <div class="block blogged" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<?php the_permalink() ?>">
                  <?php the_title(); ?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ' |'); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
            </div>
<?php } ?>
	</div>
	<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>  
      </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
