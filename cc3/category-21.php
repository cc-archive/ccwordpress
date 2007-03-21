<?php /* License jurisdiction category page */ ?>

<?php function get_the_jurisdiction($post_id) {
    $cats = get_the_category($post_id);
    $jurisdiction->code = '';
    $jurisdiction->status = '';
    foreach ( $cats as $cat ) {
       if ( $cat->category_parent == 21 ) {
           $jurisdiction->code = $cat->category_nicename;
           $jurisdiction->name = $cat->cat_name;
       }
       if ( $cat->category_parent == 17 ) {
           $jurisdiction->status = $cat->category_nicename;
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
} ?>

<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <h2>Worldwide<br />&nbsp;</h2>
        <div id="splash-menu">
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
            <p>We are excited to announce Creative Commons International &mdash; an offshoot of our licensing project dedicated to the drafting and eventual adoption of jurisdiction-specific licenses. Creative Commons International is being lead by <a href="http://creativecommons.org/about/people#65">Catharina Maracke</a> (<a href="mailto:catharina@creativecommons.org">email</a>), with help from member jurisdictions.</p>
            <h3>Completed Licenses</h3>
            <p>We have completed the process and developed licenses for the following jurisdictions:</p>
<?php 
    query_posts("orderby=title&order=ASC&posts_per_page=-1");
    if (have_posts())  { ?>
            <div class="icontainer">

<?php while (have_posts()) { the_post();  
    $jurisdiction = get_the_jurisdiction($post->ID);

    if ($jurisdiction->code == '' || $jurisdiction->status != 'completed') {
       continue;
    }
    $url = get_post_meta ($post->ID, "url", TRUE);
    $img = "/images/international/$jurisdiction->code.png";
?>
              <div class="ifloat"><a href="<?= $url ?>"><img border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>" style="border:1px solid black'" /></a><br /><p><a href="<?= $url ?>"><?= $jurisdiction->name ?></a></p></div>
<?php } ?>
            </div>
            <br clear="all" />

            <h3>Project Jurisdictions</h3>

            <p>The process of developing licenses and discussing them are still in progress for the following jurisdictions:</p>

            <div class="icontainer">
<?php rewind_posts(); while (have_posts()) { the_post();
    $jurisdiction = get_the_jurisdiction($post->ID);
    if ($jurisdiction->code == '' || $jurisdiction->status != 'in-progress') {
       continue;
    }
    $url = get_post_meta ($post->ID, "url", TRUE);
    $img = "/images/international/$jurisdiction->code.png";
?>
              <div class="ifloat"><a href="<?= $url ?>"><img border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>" style="border:1px solid black'" /></a><br /><p><a href="<?= $url ?>"><?= $jurisdiction->name ?></a></p></div>
<?php } ?>
            </div>
<br clear="all" />
<a name="more"></a>

          </div>
          <div id="features">
            <h4>Upcoming Project Jurisdictions</h4>
            <ul>
<?php rewind_posts(); while (have_posts()) { the_post();
    $jurisdiction = get_the_jurisdiction($post->ID);
    if ($jurisdiction->code == '' || $jurisdiction->status != 'upcoming') {
       continue;
    }
?>
              <li><strong><?= $jurisdiction->name ?></strong>: $jurisdiction->excerpt</li>
<?php } ?>
            </ul>
            <br />

            <h4>Upcoming Launch Dates</h4>
            <ul>
              <li>Various:  TBD</li>
            </ul>
          </div>
        </div>  
<?php } ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
