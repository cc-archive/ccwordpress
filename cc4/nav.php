<?php
/* construct nav menu */
$nav = array (
  array("name" => "Home",           "path" => get_option('home')),
  array("name" => "About",          "path" => "/about/"),
  array("name" => "Blog",           "path" => "/weblog/"),
  array("name" => "Projects",       "path" => "/projects/"),
  array("name" => "Support",        "path" => "http://support.creativecommons.org"),
  array("name" => "Participate",    "path" => "/participate/"),
  array("name" => "International",  "path" => "/international/"),
  array("name" => "Contact",        "path" => "/contact/"),
);

?>
  <div id="mainmenu">
    <ul id="navbar" class="box">
       <?php foreach($nav as $i => $item) { 
          $klass = "inactive";
          
          // dead easy to figure out where we are.
          if (is_home() and $item["name"] == "Home") $klass = "active";
          if (is_page($item["name"])) $klass = "active";

          if ($post) {
            // check if we're a child page, highlight parent tab if applicable         
            if(get_the_title($post->post_parent) == $item["name"]) $klass = "active"; 
          
            // check if we're in the ccInternational area
            if (in_category(21) and $item["name"] == "International") $klass = "active";
            if (in_category(1) and $item["name"] == "Blog" and !is_home()) $klass = "active";
          }
          
          print '      <li class="'.$klass.'"><a href="'.$item["path"].'" title="'.$item["name"].'">'.$item["name"].'</a></li>';
        } ?>
    </ul>
  </div>
 
