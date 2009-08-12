<?php 

if ( have_posts() )  {
	the_post(); 
} else {
	require (TEMPLATEPATH . '/404.php');
	exit();
}

?>

<?php get_header(); ?>

<div id="body">
	<div id="content">
		<div id="main-content">
			<div class="block" id="title">
				<h2> <?php the_title(); ?></h2>
			</div>
			<div class="block">
			<h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
			<div class="categories"><?php the_category(', ') ?></div>

			<?php the_content(); ?>
		<?php /*	
			<div class="categories"><strong>Categories</strong>: <?php the_category(', ') ?></div>
		*/ ?>
			<div class="comments"><?php comments_template(); ?></div>
		</div>
		</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
