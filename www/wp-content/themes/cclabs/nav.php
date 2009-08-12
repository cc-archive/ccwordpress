<?php
/* construct nav menu */
$nav = array (
  array("name" => "Home",           "path" => get_option('home')),
  array("name" => "Videos",			"path" => "/videos/"),
  array("name" => "Code",          "path" => "http://code.creativecommons.org"),
  array("name" => "Developers",        "path" => "http://wiki.creativecommons.org/Developers"),
  array("name" => "Contact",        "path" => "http://creativecommons.org/contact/")
);

?>
  <div id="mainmenu">
    <ul id="navbar" class="box">
       <?php foreach($nav as $i => $item) { 
          $klass = "inactive";
          
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
          
          print '      <li class="'.$klass.'"><a href="'.$item["path"].'" title="'.$item["name"].'"><span>'.$item["name"].'</span></a></li>';
        } 
        
        if (is_page() or is_single()) edit_post_link('<span>Edit this article</span>', '<li class="edit">', '</li>');
       
        ?>
        
    </ul>
    <div class="clear"></div>
  </div>
 
