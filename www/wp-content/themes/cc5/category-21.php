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

    <div id="mainContent" class="box">
      <div id="contentPrimary">
        	<div class="block" id="title">
        		<h2>CC Affiliate Network</h2>
        	</div>
        	<div id="blocks">
            <div class="block page sideContentSpace">

	<p>
        The CC Affiliate Network consists of affiliate teams in over 70
jurisdictions working together with us to serve as a hub for CC activity in 
their jurisdictions.
	</p>

	<p>
		The teams have a wide range of responsibilities, including
public outreach, community building, translating materials and tools, fielding
inquiries, conducting research, maintaining resources for CC users, and in
general, promoting sharing. Unaffiliated volunteers can also organize
<a href='http://wiki.creativecommons.org/Events'>events</a> and promote 
Creative Commons locally.
	</p>

	<p>
        If you would like to contribute or learn more click on the flags below 
or email affiliate-program@creativecommons.org.
	</p>

	<p>
        The <a href='http://wiki.creativecommons.org/Affiliates'>Affiliates page</a> of the CC
wiki contains more information, including regional activities and history of our work.
	</p>

<h3>The Licensing Suite</h3>


	<p>
		<a href='/about/licenses'>
		<img style='float: left; padding: 0 5px 0 5px;' src='http://creativecommons.org/images/international/unported.png' alt='CC License' />
		</a>
		Creative Commons offers a core suite of six copyright licenses
written to conform to international treaties governing copyright. These
international licenses (formerly known as the “Unported” suite) are ready and
intended for use around the world in their current form, without further
modification. You can think of the international license suite as appropriate
for use in all of the countries that are signatories to established
international copyright treaties. The most recent international license suite
available is <a href='http://wiki.creativecommons.org/Version_3'>version 3.0</a>.
	</p>

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
              <br clear="all" />

<!--
              <h3>In Progress Jurisdictions</h3>

              <p>The process of adapting the licenses is still in progress for the following jurisdictions:</p>

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
-->
              <a name="more"></a>

              <?php echo $block_content['more-information']; ?>
            </div>
          </div>

<!--
          <div id="sideContent">
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
-->
        </div>  
<?php } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
