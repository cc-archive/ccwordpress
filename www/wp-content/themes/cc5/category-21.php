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
    } else if ($jurisdiction->status != 'completed' && $jurisdiction->status != 'in-progress') {
       continue;
    }
    $img = "/images/international/$jurisdiction->code.png";
?>
                <div class="ifloat"><a href="/international/<?= $jurisdiction->code ?>/"><img class="flag" border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>"  /></a><br /><p><a href="/international/<?= $jurisdiction->code ?>/"><?= $jurisdiction->name ?></a></p></div>
<?php } ?>
              </div>
              <br clear="all" />

              <a name="more"></a>

              <?php echo $block_content['more-information']; ?>
            </div>
          </div>

	<div id="sideContent">
                        <h4>Jurisdiction Database</h4>
                        <p>The <a href="http://wiki.creativecommons.org/Jurisdiction_Database">Jurisdiction Database</a> contains further information about the international licenses and each jurisdiction with a Creative Commons affiliate team (e.g. <a href="http://wiki.creativecommons.org/Germany">Germany</a>, <a href="http://wiki.creativecommons.org/Estonia">Estonia</a>). On these pages you may add or edit data about the jurisdiction or data about the jurisdiction’s 3.0 license porting process. You can also <a href="http://wiki.creativecommons.org/Special:RunQuery/Jurisdiction_Query">query the database</a> for the full text of the international licenses and/or one or more ported licenses.</p>

                        <h4>CC Affiliate Teams: a Brazilian Case Study</h4>
                        <a href="/videos/cc-brasil"><img src="/images/front/ccbrasil.jpg" alt="Gilberto Gil" style="border: 2px solid #cccccc" align="center" height="136" width="136" /></a>
                        <p>One of the best ways to learn about Creative Commons and the CC Affiliate Network is to watch one of our videos. <a href="/videos/cc-brasil">This ten-minute video</a> covers a significant CC event in Brazil, the impact on the country, and the people behind the project. It's a great look at how a jurisdiction can benefit from localizing CC tools.</p>
	</div>

        </div>  
<?php } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
