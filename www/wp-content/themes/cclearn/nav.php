<?php
/* construct nav menu */
$nav = array (
//  array("name" => "Home",           "path" => "/"),
  array("name" => "About",          "path" => "/about/"),
  array("name" => "FAQ",			      "path" => "/resources/faq/"),
  array("name" => "Projects",       "path" => "/projects/"),
  array("name" => "Productions",    "path" => "/productions/"),
  array("name" => "Resources",      "path" => "/resources/"),
  array("name" => "Search",    	    "path" => "/search/"),
  array("name" => "Contact",        "path" => "/contact/")
);

?>
  <div id="mainmenu">
    <ul id="navbar" >
       <?php 
	   $activated = false;
	   foreach($nav as $i => $item) { 
          $klass = "inactive";
          
		  if (!$activated) {
	  		
			// dead easy to figure out where we are.
          	if (is_home() and $item["name"] == "Home") $klass = "active";
          	if (is_page($item["name"])) $klass = "active";
	  		if ($post->post_name == strtolower($item["name"])) $klass = "active";
	  
	  		if ($post) {
            	// check if we're a child page, highlight parent tab if applicable         
            	if(get_the_title($post->post_parent) == $item["name"]) $klass = "active"; 
          
            	// check if we're in the ccInternational area
            	if (in_category(21) and $item["name"] == "International") $klass = "active";
            
            // blog [post] detection
  //          if ((is_category(1) or (in_category(1) and is_single())) and $item["name"] == "Blog" and !is_home() and !is_page()) $klass = "active";
          	}
			if ("active" == $klass) $activated = true;
          }

          print '      <li class="'.$klass.'"><div><a href="'. get_option('home') . $item["path"] .'" title="'.$item["name"].'">'.$item["name"].'</a></div></li>';
        } 
        
        if (is_page() or is_single()) edit_post_link('<div class="edit">Edit this article</div>', '<li class="edit">', '</li>');
       
        ?>
        
    </ul>
    <div class="clear"></div>
  </div>
 
