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
		CC Affiliate Network
	</h1>
</div>

<div id="content">
	<div class="container_16">
		<div class="grid_12">
			<p>The CC Affiliate Network consists of affiliate teams in over 70 jurisdictions working together with us to serve as a hub for CC activity in their jurisdictions.</p>

			<p>The teams have a wide range of responsibilities, including public outreach, community building, translating materials and tools, fielding inquiries, conducting research, maintaining resources for CC users, and in general, promoting sharing. Unaffiliated volunteers are also welcome to organize events. and promote Creative Commons locally, regionally and globally.</p>

			<p>If you would like to contribute or learn more click on the flags below or email affiliate-program@creativecommons.org.</p>

			<p>The <a href="http://wiki.creativecommons.org/Affiliates">Affiliates page</a> of the CC wiki contains more information, including regional activities and history of our work.</p>

			<h3>The Licensing Suite</h3>
			<p>Creative Commons offers a core suite of six copyright licenses written to conform to international treaties governing copyright. These international licenses (formerly known as the “Unported” suite) are ready and intended for use around the world in their current form, without further modification. You can think of the international license suite as appropriate for use in all of the countries that are signatories to established international copyright treaties. The most recent international license suite available is <a href="http://wiki.creativecommons.org/Version_3">version 3.0</a>.</p>

			<p>Please note that CC’s policy is to not approve porting projects prior to the establishment of a robust, local community outreach program and demonstrated need and demand for a ported license suite.</p>

			<h3>CC Affiliate Network</h3>

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

			<a name="more"></a>
			<h3>More Information</h3>
			<p>Creative Commons also offers ported versions of its six, core licenses for many jurisdictions (usually jurisdiction = country, but not always). Over 50 ported license suites exist. These ported licenses are based on and compatible with the international license suite, differing only in that they have been modified to reflect local nuances in how legal terms and conditions are expressed, drafting protocols and, of course, language. The ported licenses and the international licenses are all intended to be legally effective anywhere in the world, and have the same legal effect.</p>
 
			<a title="model" name="model"></a>
			<p align="center"><img src="/images/projects/international.gif" border="0" height="453" vspace="10" width="383" /></p>


		</div>

		<div class="grid_4">
			<h4>Jurisdiction Database</h4>
			<p>The <a href="http://wiki.creativecommons.org/Jurisdiction_Database">Jurisdiction Database</a> contains further information about the international licenses and each jurisdiction with a Creative Commons affiliate team (e.g. <a href="http://wiki.creativecommons.org/Germany">Germany</a>, <a href="http://wiki.creativecommons.org/Estonia">Estonia</a>). On these pages you may add or edit data about the jurisdiction or data about the jurisdiction’s 3.0 license porting process. You can also <a href="http://wiki.creativecommons.org/Special:RunQuery/Jurisdiction_Query">query the database</a> for the full text of the international licenses and/or one or more ported licenses.</p>
			<br />

			<h4>CC Affiliate Teams: a Brazilian Case Study</h4>
			<a href="/videos/cc-brasil"><img src="/images/front/ccbrasil.jpg" alt="Gilberto Gil" style="border: 2px solid #cccccc" align="right" height="136" width="136" /></a>
			<p>One of the best ways to learn about Creative Commons and the CC Affiliate Network is to watch one of our videos. <a href="/videos/cc-brasil">This ten-minute video</a> covers a significant CC event in Brazil, the impact on the country, and the people behind the project. It's a great look at how a jurisdiction can benefit from localizing CC tools.</p>
		</div>
	</div>
</div>

<?php get_footer(); ?>

