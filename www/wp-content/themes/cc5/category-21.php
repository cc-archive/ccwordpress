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
        		<h2>International</h2>
        	</div>
        	<div id="blocks">
            <div class="block page sideContentSpace">

            <p>This page provides an overview of the Creative Commons Affiliate 
            Teams and the work they do together with CC to build and support communities 
            who use CC legal tools.</p>
 
            <p>Affiliate Teams adapt the licenses and other legal tools to the 
            languages of a jurisdiction and local copyright legislation, if 
            necessary.  Currently, there are over 50 jurisdictions with official Affiliate 
            Teams, with several more in process. The Affiliate Teams have a wide range of 
            responsibilities, including building communities, translating materials and 
            tools, maintaining resources for CC users, and in general, promoting legal 
            sharing. They serve as the hub for CC activity in their jurisdictions.</p>

            <p>If you would like to contribute to a jurisdiction's Affiliate 
            Team, you'll find contact information for the team by clicking on the flags 
            below. If you don't see your jurisdiction listed, or wish to contribute to or 
            comment on CC's international efforts in another way, please email 
            affiliate-program@creativecmmons.org. Please note that CC has established a 
            policy against porting of its licenses in jurisdictions prior to the 
            establishment of a robust, local community outreach program. CC approves such 
            proposals only after a thorough review of the rationale and need for that 
            localization.</p>

            <p>More information about the CC Affiliate Network can be found at 
            <a href="http://wiki.creativecommons.org/Affiliates">http://wiki.creativecommons.org/Affiliates</a>.
            </p>

              <h3>Completed Licenses</h3>
              <p>The CC licensing suite has been adapted in the following jurisdictions:</p>

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
              <a name="more"></a>

              <?php echo $block_content['more-information']; ?>
            </div>
          </div>

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
        </div>  
<?php } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
