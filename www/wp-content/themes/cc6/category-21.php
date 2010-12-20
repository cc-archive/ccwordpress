<?php /* License jurisdiction category page */ ?>
<?php function get_the_jurisdiction($post_id) {
	$cats = get_the_category($post_id);
	$jurisdiction->code = '';
	$jurisdiction->status = '';
	$jurisdiction->name = '';
	foreach ( $cats as $cat ) {
		if ( $cat->category_parent == 21 ) {
			$jurisdiction->code = $cat->category_nicename;
			$jurisdiction->name = $cat->cat_name;
		}
		if ( $cat->cat_ID == 18 ) {
			$jurisdiction->status = 'upcoming';
		} else if ( $cat->cat_ID == 19 ) {
			$jurisdiction->status = 'in-progress';
		} else if ( $cat->cat_ID == 20 ) {
			$jurisdiction->status = 'completed';
		}
	}
	return $jurisdiction;
} 

$is_international = true;

?>
<?php get_header(); ?>

<div id="title" class="container_16">
	<h1 class="grid_16">
		International
	</h1>
</div>

<div id="content">
	<div class="container_16">
		<div class="grid_12">
			<p>Creative Commons works to "port" the core Creative Commons Licenses to different copyright legislations around the world. The porting process involves both linguistically translating the licenses and legally adapting them to particular jurisdictions.</p>

			<p>This work is led by CC General Counsel <a href="http://creativecommons.org/about/people#95">Diane Peters</a> (<a href="mailto:diane@creativecommons.org">email</a>) with Project Manager <a href="http://creativecommons.org/about/people#85">Michelle Thorne</a> (<a href="mailto:michelle@creativecommons.org">email</a>) and volunteer teams in each jurisdiction who are committed to introducing CC to their country and who consult extensively with members of the public and key stakeholders in an effort to adapt the CC licenses to their jurisdiction.</p>

			<p>A complete overview of the porting process can be found at <a href="http://wiki.creativecommons.org/International_Overview">http://wiki.creativecommons.org/International_Overview</a>.</p>

			<h3>Completed Licenses</h3>
			<p>We have completed the process and developed licenses for the following jurisdictions:</p>
			<?php 
			query_posts("cat=21&orderby=title&order=ASC&posts_per_page=-1");
			if (have_posts())  { ?>
			<div class="icontainer">

				<?php while (have_posts()) { the_post();  
				$jurisdiction = get_the_jurisdiction($post->ID);

				if ($jurisdiction->code == '' || $jurisdiction->status == '') {
					// Store post content for placement elsewhere in site
					$block_content[$post->post_name] = $post->post_content;
					continue;
				} else if ($jurisdiction->status != 'completed') {
					continue;
				}
				$img = "/images/international/$jurisdiction->code.png";
				?>
				<div class="ifloat"><a href="/international/<?= $jurisdiction->code ?>/"><img class="flag" border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>"  /></a><br /><p><a href="/international/<?= $jurisdiction->code ?>/"><?= $jurisdiction->name ?></a></p></div>
				<?php } ?>
			</div>
			<?php } ?>

			<br clear="all" />

			<h3>Project Jurisdictions</h3>

			<p>The process of developing licenses and discussing them are still in progress for the following jurisdictions:</p>

			<div class="icontainer">
				<?php rewind_posts(); while (have_posts()) { the_post();
				$jurisdiction = get_the_jurisdiction($post->ID);
				if ($jurisdiction->code == '' || $jurisdiction->status != 'in-progress') {
					continue;
				}
				$img = "/images/international/$jurisdiction->code.png";
				?>
				<div class="ifloat"><a href="/international/<?= $jurisdiction->code ?>/"><img class="flag" border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>" /></a><br /><p><a href="/international/<?= $jurisdiction->code ?>/"><?= $jurisdiction->name ?></a></p></div>
				<?php } ?>
			</div>
			<br clear="all" />
			<a name="more"></a>

			<?php echo $block_content['more-information']; ?>
		</div>

		<div class="grid_4">
			<h4>Upcoming Project Jurisdictions</h4>
			<ul>
				<?php rewind_posts(); while (have_posts()) { the_post();
				$jurisdiction = get_the_jurisdiction($post->ID);
				if ($jurisdiction->code == '' || $jurisdiction->status != 'upcoming') {
					continue;
				}
				?>
				<li><strong><?= $jurisdiction->name ?></strong>: <?= $post->post_excerpt ?></li>
				<?php } ?>
			</ul>
			<br />

			<?php echo $block_content['upcoming-launch-dates']; ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>

