<?php 
// "Single" template will always, by definition, have a single post.
// I'm quite sure this will not change, except on opposites day, perhaps.
if (have_posts())  {
    the_post(); 
} else {
  require (TEMPLATEPATH . '/404.php');
  exit();
} ?>
<?php get_header(); ?>
    <?php
     // check worldwide categories
     in_category(18) ? $is_worldwide_upcoming = true : $is_worldwide_upcoming = false;
     in_category(19) ? $is_worldwide_in_progress = true : $is_worldwide_in_progress = false;
     in_category(20) ? $is_worldwide_completed = true : $is_worldwide_completed = false;
     //in_category(21) ? $is_worldwide = true : $is_worldwide = false;

     foreach ((get_the_category()) as $cat) {
       if ($cat->category_parent == 21) {
         $jurisdiction_name = $cat->cat_name;
         $jurisdiction_code = $cat->category_nicename;
       }
     }
    ?>
  
    <div id="mainContent" class="box single">
      <div id="contentPrimary">
    			<div class="block" id="title">
						<h3 class="category">
							<a href="<?php echo get_settings('home') . "/" ?>international">
								Creative Commons International 
							</a>
						</h3>

<? if ($jurisdiction_code != '') { ?>
						<h2>
							<img src="/images/international/<?php echo $jurisdiction_code ?>.png" alt="<?php echo $jurisdiction_code ?> flag" class="flag" /><?php 
								the_title(); ?>
							</h2>
<? } ?>
					</div>
    
            <div class="block international" id="post-<?php the_ID(); ?>">
<? 
     $license_xml = new LicenseXml();

     $jurisdiction_site = $license_xml->getJurisdictionSite($jurisdiction_code);

     if ($jurisdiction_site) {
?>

<div class="licensebox" style="margin:14px;">
Visit the jurisdiction site <a href="<?=$jurisdiction_site?>">here</a>.
</div>
<? 
     }
 
?>

<? if ($is_worldwide_completed) { ?>
              <div class="licensebox" style="margin:14px;">
                <p>The <? echo $jurisdiction_name ?> license has now been integrated 
                into <a href="/choose/?jurisdiction=<?php echo $jurisdiction_code ?>">the Creative 
                Commons licensing process</a>, so you are able to license your works under this 
                jurisdiction's law. </p> 
                <p>The latest version of the licenses available for this jurisdiction are:</p>
                <ul>
                  <?php
                          $licenses = $license_xml->getLicensesCurrent($jurisdiction_code);
                          foreach ($licenses as $l) {
                              $l[name] = $license_xml->getLicenseName($l[uri]);
                              echo "<li><a href='$l[uri]'>$l[name]</a></li>\n";
                          }
                      /* } */ ?>
                </ul>
                <p>Many thanks to all who contributed to the license-porting process. This page 
                remains for reference.</p>
                <p>Please take a look at the mailing-list archive if you are interested in the 
                academic discussion leading to the <span><?php echo $jurisdiction_name ?></span> 
                final license.</p>
              </div>
              <? } ?>
			  <?php the_content(); ?>

        <?php 
        	dynamic_sidebar('Single Post');
        ?>

          </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
