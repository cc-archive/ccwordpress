<div id="sidebar">
<div id="help_international_list" class="sideitem">
 <div class="bd">
   <select id="international" name="sortby" onchange="orderby(this)">    
     <option value=""><?php _e('Select a jurisdiction', 'cc5'); ?></option>
     <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
   </select>
 </div>
</div>

<?php  
  if (is_page() or is_single()) {
    edit_post_link('<span>Edit this article...</span>', '<div class="sideitem">', '</div>'); 
  }
?>
<?php if (!is_search()) { ?>
<div class="sideitem">
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
      <div>
        <input type="text" value="" name="s" id="s" class="inactive" /> <input type="submit" id="searchsubmit" value="<?php _e('Go', 'cc5'); ?>" />
      </div>
    </form>

    <div class="clear"></div>
  </div>
<?php } ?>
  <div class="sideitem">
    <ul>
      <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/license-alt.png" alt="<?php _e('License your work', 'cc5'); ?>" />&nbsp;&nbsp;<a href="/choose"><?php _e('License your work', 'cc5'); ?></a></li>
      <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/find-alt.png" alt="<?php _e('License your work', 'cc5'); ?>" width="12" height="12" />&nbsp;&nbsp;<a href="http://search.creativecommons.org/"><?php _e('Find licensed works', 'cc5'); ?></a></li>
    </ul>
  </div>
  
  <?php
  	$exclude_list = "7486,7476,7471,7472,7473,7506,7474,7475,7487,7505,7682,7793,7794,12354,7499,7502,7501";
  	$list_pages_query = "&title_li=&echo=0&exclude=".$exclude_list;
  	if ($post->post_parent) {
  		if ($root_post_id = get_post_meta($post->ID, "root", true)) {
  			$child_id = $root_post_id;
  		} else {
  			$child_id = $post->post_parent;
  		}
  	} else {
  		$child_id = $post->ID;
  	}

  	if ($child_id) { $pages_list = wp_list_pages('child_of='.$child_id.$list_pages_query); }

      if ($pages_list) {
  		$pages_list = "<div class=\"sideitem\"><ul>" . $pages_list . "</ul></div>";
  		echo $pages_list;
  	}
   ?> 
<div class="sideitem">
    <ul>
    	<?php include "sidelinks.php"; ?>
    </ul>
  </div>

</div>

</div>
