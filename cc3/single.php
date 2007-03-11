<?php get_header(); ?>

<?php if (have_posts())  { ?>
  <?php while (have_posts()) { 
    the_post(); ?>

    <?php
     // check if this single is a commoner or blog post
     in_category(7) ? $is_commoner = true : $is_commoner = false;
     in_category(1) ? $is_blog = true : $is_blog = false;

     // check worldwide categories
     in_category(17) ? $is_worldwide = true : $is_worldwide = false;
     in_category(62) ? $is_worldwide_completed = true : $is_worldwide_completed = false;
     in_category(63) ? $is_worldwide_in_progress = true : $is_worldwide_in_progress = false;
     in_category(64) ? $is_worldwide_upcoming = true : $is_worldwide_upcoming = false;

     foreach ((get_the_category()) as $cat) {
       if ($cat->category_parent == 17) {
         $jurisdiction_name = $cat->cat_name;
         $jurisdiction_code = $cat->category_nicename;
       }
     }
    ?>
  
    <div id="body">
      <div id="splash">
        <!--img src="images/info.png" align="left"/-->
        <? if ($is_commoner) {?>
          <h3 class="category">
            <a href="<?php echo get_settings('home') . "/" . $category_name; ?>">
              <? $cat = get_the_category(); $cat = $cat[1]; echo $cat->cat_name; ?>
            </a>
	  </h3>
	<? } else if ($is_worldwide) { ?>
	  <h3 class="category">
	    <a href="<?php echo get_settings('home') . "/" ?>worldwide?">
	      Creative Commons Worldwide
            </a>
	  </h3>
        <? } else if ($category_name == "weblog") { ?>
          <h3 class="category">
            <a href="<?php echo get_settings('home') . "/" ?>weblog/">
              weblog
            </a>
          </h3>
        <? } else if ($category_name == "press-releases") { ?>
          <h3 class="category">
            <a href="<?php echo get_settings('home') . "/" ?>press-releases/">
              press-releases 
            </a>
          </h3>
        <? } ?>

	<? if ($is_worldwide) { ?>
        <h2> 
          <img src="/images/international/<?php echo $jurisdiction_code ?>.png" /><?php 
          the_title(); ?><br/>&nbsp;
        </h2>
	<? } else { ?>
        <h2> <?php the_title(); ?><br/>&nbsp;</h2>
	<? } ?>
        <div id="splash-menu">
         <?php edit_post_link('<h3>Edit this article</h3>', '', ''); ?>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <? if (!$is_commoner) {?>
          <div id="page">
          <? } else { ?>
          <div id="blog">
          <? } ?>
    
            <div class="post" id="post-<?php the_ID(); ?>">
              <? if (!$is_worldwide) { ?>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <? } ?>

              <? if ($is_worldwide_completed) { ?>
              <div class="licensebox" style="margin:14px;">
                <p>The <span><? echo $jurisdiction_name ?></span> license has now been integrated 
                into <a href="/license/?jurisdiction=<?php echo $jurisdiction_code ?>">the Creative 
                Commons licensing process</a>, so you are able to license your works under this 
                jurisdiction's law. </p> 
                <p>The latest version of the licenses available for this jurisdiction are:</p>
                <ul>
                  <li>
                  <?php
                      $license_fname = "../license_xsl/licenses.xml";
                      if (! file_exists($license_fname)) {
                          echo "<li>Unknown</li>\n";
                      }
                      $license_xml = new LicenseXml($license_fname);
                      $licenses = $license_xml->getLicensesCurrent($jurisdiction);
                      foreach ($licenses as $l) {
                          echo "<li><a href='$l[uri]'>$l['id'] $l['version']</a></li>\n";
                      } ?>
                  TBD
                  </li>
                </ul>
                <p>Many thanks to all who contributed to the license-porting process. This page 
                remains for reference.</p>
                <p>Please take a look at the mailing-list archive if you are interested in the 
                academic discussion leading to the <span><?php echo $jurisdiction_name ?></span> 
                final license.</p>
              </div>
              <? } ?>
              <?php the_content(); ?>
              <div class="comments"><?php if ($is_blog) comments_template(); ?></div>
            </div>
          
          <? if ($is_commoner) { ?>
          </div>
          <div id="features">
            <? if ($attach = cc_get_attachment ($post->ID)) { ?>
            <img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0"/><br/>
            <h3><?= the_title() ?></h3>
            <? } ?>
          <? }?>
<?php } }?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
