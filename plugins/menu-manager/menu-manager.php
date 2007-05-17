<?php
/*
Plugin Name: Menu Manager
Plugin URI: http://www.flipcorp.com
Description: Menu Manager is a menu manager for WordPress
Author: Flip Media
Author URI: http://www.flipcorp.com
Version: 2.0.1

* Modified by Alex Roberts <alex@creativecommons.org> - Added a $no_ul arg to wpfm_create to make 
* function call not send <ul></ul> tag on output, so menu items can be added in the template.
*/ 

/*
 License:
 ==============================================================================
 Copyright 2005  Flip Media FZ LLC, Dubai. www.flipcorp.com

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

function wpfm_add_management_page() {
	add_management_page('Menu Management', 'Menu Manager', 9, "menu-manager.php", "wpfm_manage");
}

$mm_item_types['category'] = "Category";
$mm_item_types['post'] = "Post Item";
$mm_item_types['link'] = "External Link";
$mm_item_types['page'] = "Static Page";
$mm_item_types['separator'] = "Separator";

$mm_item_targets['_self'] = "_self";
$mm_item_targets['_blank'] = "_blank";
$mm_item_targets['_top'] = "_top";
$mm_item_targets['_parent'] = "_parent";

$wpfm_table = $table_prefix . "flipmenu";
$wpfm_table_items = $table_prefix . "flipmenu_items";
$wpfm_location = get_option('siteurl') . '/wp-admin/edit.php?page=menu-manager.php'; // Form Action URI


function wpfm_create($menu_name, $current_class = true, $type = 'list', $no_ul = false, $select_first_option = array(), $select_attributes = "" ) {
	global $wpdb, $wp_query;
	global $wpfm_table, $wpfm_table_items, $ws_current_item, $wpfm_isfirst, $wpfm_insertscript;
	
	$ws_current_item['id'] = '';
	$ws_current_item['type'] = '';
	
	if(isset($_GET['p']) || isset($_GET['page_id']) || isset($_GET['cat'])) {
		if(isset($_GET['cat']) && $wp_query->is_category) {
			$id = $_GET['cat'];
			$meta_type = 'category';
		} elseif(isset($_GET['p']) && $wp_query->is_single) {
			$id =  $_GET['p'];
			$meta_type = 'post';
		} elseif(isset($_GET['page_id']) && $wp_query->is_page) {
			$id = $_GET['page_id'];
			$meta_type = 'page';
		}
		$ws_current_item['id'] = $id;
		$ws_current_item['type'] = $meta_type;
	} else {
		if(isset($_GET['category_name'])  && $wp_query->is_category) {
			$categories = split("/", $_GET['category_name']);
			if($categories[count($categories)-1] == "") {
				array_pop($categories);	
			}
			$slug =  array_pop($categories);
			$meta_type = 'category';
		} elseif(isset($_GET['name'])  && $wp_query->is_single) {
			$slug =  $_GET['name'];
			$meta_type = 'post';
		} elseif(isset($_GET['pagename'])  && $wp_query->is_page) {
			$slug = $_GET['pagename'];
			$meta_type = 'page';
		}
		
		if($meta_type == 'post' || $meta_type == 'page') {
			$id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'");	
		} elseif ($meta_type == 'category') {
			$id = $wpdb->get_var("SELECT cat_ID FROM $wpdb->categories WHERE category_nicename = '$slug'");	
		}
		
		if(isset($id)) {
			$ws_current_item['id'] = $id;
		}
		
		if(isset($meta_type)) {
			$ws_current_item['type'] = $meta_type;
		}
	}
	
	$ws_current_item = apply_filters('mm_current_item', $ws_current_item);
	
	$menu = $wpdb->get_row("SELECT * FROM $wpfm_table WHERE name = '$menu_name'");
	
	if(empty($menu)) {
		return;
	}
	
	$cached = wp_cache_get($menu_name, 'menu-manager');
	
	if ($cached !== false) {
		$menu_array = $cached;
	} else {
		$menu_array = wpfm_get_menu($menu->id, 0, $current_class);
	}
			
	$wpfm_isfirst = true;
	//$menu_array = wpfm_set_current($menu_array);
	
	if($type == 'select'){
		if(!$wpfm_insertscript){
			echo '
			<script language="JavaScript" type="text/JavaScript">
		<!--
		function WSMM_jumpMenu(targ,selObj,restore){ //v3.0
		  eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
		  if (restore) selObj.selectedIndex=0;
		}
		//-->
		</script>';
			$wpfm_insertscript = true;
		}
		$list_item = wpfm_selectmenu($menu_array);
		$list_id = (!empty($menu->list_id))? " id = \"$menu->list_id\"" : "";
		$list_class = (!empty($menu->list_class))? " class = \"$menu->list_class\"" : "";
		if(!empty($select_first_option)) {
			$first_option = "\n<option value\"$select_first_option[0]\">$select_first_option[1]</option>";
		} else {
			$first_option = "";
		}
		$list = $menu->insert_before."<select name=\"$menu->list_id\" $list_id $list_class onChange=\"WSMM_jumpMenu('parent',this,0)\">".$first_option.$list_item."</select>".$menu->insert_after;
		
	} else {
		$list_item = wpfm_listmenu($menu_array);
		$list_id = (!empty($menu->list_id))? " id = \"$menu->list_id\"" : "";
		$list_class = (!empty($menu->list_class))? " class = \"$menu->list_class\"" : "";
		if ($no_ul == true) {
			$list = $menu->insert_before.$list_item.$menu->insert_after;
		} else {
			$list = $menu->insert_before."<ul $list_id $list_class>".$list_item."</ul>".$menu->insert_after;
		}
	}
	if(!$cached) {
		wp_cache_add($menu_name, $menu_array, 'menu-manager', $expire = 0);
	}
	
	return $list;
}

function wpfm_listmenu($menu_array) {
	global $wpfm_isfirst;
	if(!$menu_array) {
		return "";
	}
	$close_ul = false;
	if($wpfm_isfirst == false) {
		$menu_items = "\n<ul>";
		$close_ul = true;
	}
	$wpfm_isfirst = false;
	
	foreach ($menu_array as $menu) {
		if($menu['link']) {
			$menu_items .= "\n<li $menu[class]><a href=\"$menu[link]\" $menu[target]>$menu[title]</a>";
		} else {
			$menu_items .= "\n<li $menu[class]>$menu[title]";
		}
		$menu_items .= wpfm_listmenu($menu['children']);
		$menu_items .= "</li>";
	}
	
	
	if($close_ul) {
		$menu_items .= "\n</ul>";
		$close_ul = false;
	}
	return $menu_items;
}

function wpfm_selectmenu($menu_array){
	global $wpfm_selectlevel;

	if(!$menu_array) {
		return "";
	}
	
	if(isset($wpfm_selectlevel)) {
		$wpfm_selectlevel++;
	} else {
		$wpfm_selectlevel = 0;
	}
	$level_string = str_repeat("--",$wpfm_selectlevel);
	foreach ($menu_array as $menu) {
		if($menu['current']) {
			$menu_items .= "\n<option value=\"$menu[link]\" selected>$level_string $menu[title]</option>";
		} else {
			$menu_items .= "\n<option value=\"$menu[link]\">$level_string $menu[title]</option>";
		}
		$menu_items .= wpfm_selectmenu($menu['children']);
	}
	
	$wpfm_selectlevel--;

	return $menu_items;
}

function wpfm_get_menu($menu_id, $parent_id=0, $current_class = true, $level = 0) {
	global $wpdb;
	global $wpfm_table, $wpfm_table_items, $ws_current_item;	
	
	$menu_items = $wpdb->get_results("SELECT * FROM $wpfm_table_items WHERE menu_id = '$menu_id' AND published = 1 AND parent_id = '$parent_id' ORDER BY ordering ASC");
	if(!empty($menu_items)) {
		foreach ($menu_items as $item) {
			$item_class = (!empty($item->class))? " class = \"$item->class\"" : "";
			
			$target = (!empty($item->target))? " target = \"$item->target\"" : "";
			
			switch ($item->item_type) {
				case 'category':
					$item_link = get_category_link($item->link_id);
					break;
					
				case 'post':
					$item_link = apply_filters('the_permalink', get_permalink($item->link_id));
					break;
					
				case 'page':
					$item_link = get_page_link($item->link_id);
					break;
				
				case 'link':
					$item_link = get_wpfm_link($item->link_id);
					break;
				case 'separator':
					$item_link = false;
					break;					
				default:
					$item_link = "#";
					break;
			}
			
			$item_link = apply_filters("mm_item_link", $item_link, $item);
			
			$item->name = apply_filters('mm_the_title',$item->name, $level);
			
			$level = $level + 1;
			
			$menu_array[] = array("title" => $item->name, "link" => $item_link, "target" => $target, "class" => $item_class, "link_id" => $item->link_id, "item_type" => $item->item_type, "image" => $item->use_image, "children" => wpfm_get_menu($menu_id, $item->id, $current_class, $level));
			
		}
		return $menu_array;
	} else {
		return false;	
	}
}

function wpfm_set_current($menu_array) {
	
	global $ws_current_item;
	
	foreach ($menu_array as $item) {
		
		if($item["item_type"] == $ws_current_item["type"] && $item["link_id"] == $ws_current_item["id"]) {
			$item["current"] = true;
			$item["class"] .= " current";
		} else {
			$item["current"] = false;
		}
		
		if($item["children"]) {
			$item["children"] = wpfm_set_current($item["children"]);
		}
		
		$new_menu_array[] = $item;
		
	}
	return $new_menu_array;
}

function wpfm_manage() {
	global $wpdb;
	global $user_level, $wpfm_location;
	global $wpfm_table, $wpfm_table_items;
	global $mm_item_targets, $mm_item_types;
	
	$mm_item_types = apply_filters('mm_item_types', $mm_item_types);
	$mm_item_targets = apply_filters('mm_item_targets', $mm_item_targets);
	
	
	if($wpdb->query("CREATE TABLE IF NOT EXISTS $wpfm_table (
					  `id` int(5) NOT NULL auto_increment,
					  `name` varchar(250) default NULL,
					  `hierarchical` int(1) default NULL,
					  `insert_before` text,
					  `insert_after` text,
					  `list_id` varchar(50) default NULL,
					  `list_class` varchar(50) default NULL,
					  PRIMARY KEY  (`id`)
					) TYPE=MyISAM;"));	
	
	if($wpdb->query("CREATE TABLE IF NOT EXISTS $wpfm_table_items (
					  `id` int(5) NOT NULL auto_increment,
					  `name` varchar(250) default NULL,
					  `menu_id` int(5) default '0',
					  `parent_id` int(5) default '0',
					  `item_type` varchar(10) default NULL,
					  `link_id` int(5) default '0',
					  `target` varchar(10) default NULL,
					  `class` varchar(30) default NULL,
					  `ordering` int(5) default '0',
					  `published` int(1) default '0',
					  `use_image` int(1) default '0',
					  PRIMARY KEY  (`id`)
					) TYPE=MyISAM;"));
					
	
		
		switch ($_REQUEST['action']) {
			case "addnew":
				$name = $_POST['name'];
				$hierarchical = (isset($_POST['hierarchical']))? 1 : 0;
				$insert_before = $_POST['insert_before'];
				$after = $_POST['after'];
				$list_id = $_POST['list_id'];
				$list_class = $_POST['list_class'];
				
				$wpdb->query("INSERT INTO $wpfm_table ( `name`, `hierarchical`, `insert_before`, `insert_after`, `list_id`, `list_class` ) VALUES ('$name', '$hierarchical','$insert_before','$insert_after', '$list_id', '$list_class')");
				wpfm_showmenulist();
				wpfm_menuform();				
				break;
		
			case "editmenu":
				$menu_id = $_REQUEST['menu_id'];
				$result = $wpdb->get_row("SELECT * FROM $wpfm_table WHERE id = '$menu_id'");
				if( !empty($result) ) {
				$hierarchical = ($result->hierarchical == 1) ? ' checked' : '';
				wpfm_menuform($result->id, $result->name, $result->list_id, $result->list_class, $result->insert_before, $result->insert_after, $hierarchical, 'editmenusave', 'Update Menu');
				} else {
					wpfm_showmenulist();
					wpfm_menuform();	
				}
				break;
				
			case "editmenusave":
				$menu_id = $_POST['menu_id'];
				$name = $_POST['name'];
				$hierarchical = (isset($_POST['hierarchical']))? 1 : 0;
				$insert_before = $_POST['insert_before'];
				$insert_after = $_POST['insert_after'];
				$list_id = $_POST['list_id'];
				$list_class = $_POST['list_class'];
				
				$wpdb->query("UPDATE $wpfm_table SET `name` = '$name', `hierarchical` = '$hierarchical', `insert_before` = '$insert_before', `insert_after` = '$insert_after', `list_id` = '$list_id', `list_class` = '$list_class' WHERE id = '$menu_id'");
				wp_cache_delete($name,'menu-manager');
				wpfm_showmenulist();
				wpfm_menuform();				
			
				break;
				
			case 'delete':
				$menu_id = $_GET['menu_id'];
				
				$menu_name = $wpdb->get_var("SELECT name FROM $wpfm_table WHERE id = '$menu_id'");
				
				wp_cache_delete($menu_name,'menu-manager');
				
				$wpdb->query("DELETE FROM $wpfm_table_items WHERE menu_id = '$menu_id'");
				$wpdb->query("DELETE FROM $wpfm_table WHERE id = '$menu_id'");
				wpfm_showmenulist();
				wpfm_menuform();	
				break;
				
			case 'deleteitems':
				if(!isset($_POST['item_ids'])) {
					break;	
				}
				$menu_id = $_POST['menu_id'];
				$item_ids = implode(",", $_POST['item_ids']);
				$wpdb->query("DELETE FROM $wpfm_table_items WHERE id IN ($item_ids)");
				
				$menu_name = $wpdb->get_var("SELECT name FROM $wpfm_table WHERE id = '$menu_id'");
				wp_cache_delete($menu_name,'menu-manager');
				
				wpfm_showitems($menu_id);
				wpfm_menuitemform($menu_id);
				break;
				
			case "showitems":
				$menu_id = $_REQUEST['menu_id'];	
				wpfm_showitems($menu_id);
				wpfm_menuitemform($menu_id);
			
				break;	
			
			case "setpublish":
				$menu_id = $_REQUEST['menu_id'];
				$item_id = $_REQUEST['item_id'];
				$published = $_REQUEST['published'];
				
				$wpdb->query("UPDATE $wpfm_table_items SET published = '$published' WHERE id = '$item_id'");
				
				$menu_name = $wpdb->get_var("SELECT name FROM $wpfm_table WHERE id = '$menu_id'");
				wp_cache_delete($menu_name,'menu-manager');

				
				wpfm_showitems($menu_id);
				wpfm_menuitemform($menu_id);
			
				break;
			
			case "setordering":
				$item_id = $_REQUEST['item_id'];
				$menu_id = $_REQUEST['menu_id'];
				$parent_id = $_REQUEST['parent_id'];
				$current_ordering = $wpdb->get_var("SELECT ordering FROM $wpfm_table_items WHERE id = '$item_id'");

				if($_REQUEST['order'] == 'up') {
					
						$other_item_id = $wpdb->get_var("SELECT id FROM $wpfm_table_items WHERE parent_id = '$parent_id' AND menu_id = '$menu_id' AND ordering < $current_ordering ORDER BY ordering DESC");
						
						if(!empty($other_item_id)) {
						
							$wpdb->query("UPDATE $wpfm_table_items SET ordering = '$current_ordering' WHERE id = '$other_item_id'");
							$wpdb->query("UPDATE $wpfm_table_items SET ordering = '".($current_ordering-1)."' WHERE id = '$item_id'");
						}
					
					
				} else {
					
						$other_item_id = $wpdb->get_var("SELECT id FROM $wpfm_table_items WHERE parent_id = '$parent_id' AND menu_id = '$menu_id' AND ordering > $current_ordering ORDER BY ordering ASC");
						
						if(!empty($other_item_id)) {
						
							$wpdb->query("UPDATE $wpfm_table_items SET ordering = '$current_ordering' WHERE id = '$other_item_id'");
							$wpdb->query("UPDATE $wpfm_table_items SET ordering = '".($current_ordering+1)."' WHERE id = '$item_id'");
						}
					
				}
				
				$results = $wpdb->get_col("SELECT id from $wpfm_table_items WHERE parent_id = '$parent_id' AND menu_id = '$menu_id' ORDER BY ordering ASC");
				for( $i = 1; $i <= count($results); $i++ ) {
					$wpdb->query("UPDATE $wpfm_table_items SET ordering = '$i' WHERE id = '".$results[$i-1]."'");
				}	
				
				$menu_name = $wpdb->get_var("SELECT name FROM $wpfm_table WHERE id = '$menu_id'");
				wp_cache_delete($menu_name,'menu-manager');

				wpfm_showitems($menu_id);
				wpfm_menuitemform($menu_id);
				
				break;
			
				
			case "addmenuitem":
				$menu_id = $_POST['menu_id'];
				$name = $_POST['name'];
				$parent_id = $_POST['parent_id'];
				$item_type = $_POST['item_type'];
				$link_id = $_POST['link_id'];
				$target = $_POST['target'];
				$class = $_POST['class'];
				$action = $_POST['action'];
				$published = (isset($_POST['published']))? 1 : 0;
				$use_image = (isset($_POST['use_image']))? 1 : 0;
			
				if($_POST['op'] == 'populate') {
					wpfm_menuitemform($menu_id,$name,$parent_id,$target,$class,$published,$use_image,$item_type,$link_id,$action,$submit);
					break;
				}
				
				$wpdb->query("
				INSERT INTO $wpfm_table_items (name, menu_id, parent_id, item_type, link_id, target, class, ordering, published, use_image) 
				VALUES ('$name', '$menu_id', '$parent_id', '$item_type', '$link_id', '$target', '$class', '999', '$published', '$use_image')
				");
				
				$menu_name = $wpdb->get_var("SELECT name FROM $wpfm_table WHERE id = '$menu_id'");
				wp_cache_delete($menu_name,'menu-manager');
				
				wpfm_showitems($menu_id);
				wpfm_menuitemform($menu_id);
			
			
				break;	
				
				case "editmenuitem":
				

				
				if(!isset($_POST['item_id'])) {
					$item_id = $_GET['item_id'];
					$menu_item = $wpdb->get_row("SELECT * FROM $wpfm_table_items WHERE id = '$item_id'");
										
					wpfm_menuitemform($menu_item->menu_id,$menu_item->name,$menu_item->parent_id,$menu_item->target,$menu_item->class,$menu_item->published,$menu_item->use_image,$menu_item->item_type,$menu_item->link_id,'editmenuitem', $item_id);
					break;
				} else {
						
						$menu_id = $_POST['menu_id'];
						$item_id = $_POST['item_id'];
						
						$menu_name = $wpdb->get_var("SELECT name FROM $wpfm_table WHERE id = '$menu_id'");
						wp_cache_delete($menu_name,'menu-manager');

						$name = $_POST['name'];
						$parent_id = $_POST['parent_id'];
						$item_type = $_POST['item_type'];
						$link_id = $_POST['link_id'];
						$target = $_POST['target'];
						$class = $_POST['class'];
						$action = $_POST['action'];
						$published = (isset($_POST['published']))? 1 : 0;
						$use_image = (isset($_POST['use_image']))? 1 : 0;
						
						if($_POST['op'] == 'populate') {
							wpfm_menuitemform($menu_id,$name,$parent_id,$target,$class,$published,$use_image,$item_type,$link_id,$action,$item_id);
							break;
						}
						
						$wpdb->query("
						UPDATE $wpfm_table_items SET name='$name', menu_id='$menu_id', parent_id='$parent_id', item_type='$item_type', link_id='$link_id', target='$target', class='$class', published='$published', use_image='$use_image' WHERE id = '$item_id'
						");
						
					
				}
			

				
				wpfm_showitems($menu_id);
				wpfm_menuitemform($menu_id);
			
			
				break;	
				
			default:
				
			
				wpfm_showmenulist();
				wpfm_menuform();	
			
				break;
		}
		
}

function wpfm_menuform($menu_id = '', $name = "", $list_id = "", $list_class = "", $insert_before = "", $insert_after = "", $hierarchical = ' checked', $action = 'addnew', $submit = 'Add New') {	
	
?>
<div class="wrap">
<h2> Add New Menu </h2>
<form action="<?php echo $wpfm_location; ?>" method="POST">
<p>
Name:<br>
<input name="name" type="text" size="30" value="<?php echo $name; ?>">
</p>
<p>
List ID:<br>
<input name="list_id" type="text" size="30" value="<?php echo $list_id; ?>">
</p>
<p>
List Class:<br>
<input name="list_class" type="text" size="30" value="<?php echo $list_class; ?>">

</p>
<p>
Insert Before:<br>
<textarea name="insert_before" cols="30" rows="4"><?php echo $insert_before; ?></textarea>
</p>
<p>
Insert after:<br>
<textarea name="insert_after" cols="30" rows="4"><?php echo $insert_after; ?></textarea>
</p>
<p>

<input type="checkbox" name="hierarchical" value="1" <?php echo $hierarchical; ?> /> Hierarchical
</p>
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
<p class="submit">
<input type="submit" value="<?php echo $submit; ?>">
</p>
</form>

</div>

<?php
}

function wpfm_menuitemform($menu_id, $name = "",  $parent_id = 0, $target = '', $class = '', $published = 0, $use_image = 0, $item_type = '', $link_id = '', $action = 'addmenuitem', $item_id = '' ) {	
	global $mm_item_types, $mm_item_targets, $wpfm_location;
	
	$submit = ($item_id == '')?'Add New' : 'Update';
	
	$published = ($published == 1) ? ' checked' : '';
	$use_image = ($use_image == 1) ? ' checked' : '';
?>
<script src="<?php bloginfo("siteurl"); ?>/wp-content/plugins/menu-manager/filterlist.js" type="text/javascript"></script>
<script language="javascript">
<!--
function fnPopulate(ent) {
	ent.op.value = "populate";
	ent.submit();	
}

//-->
</script>
<div class="wrap">
<h2> Add New Menu Item </h2>
<form action="<?php echo $wpfm_location; ?>" method="POST" name="frmNewMenuItem" id="frmNewMenuItem">
<p>
Name:<br>
<input name="name" type="text" size="30" value="<?php echo $name; ?>"><br />
<input type="checkbox" name="use_image" value="1" <?php echo $use_image; ?> /> Use Image
</p>
<p>
Parent:<br>
<?php wpfm_selectparent('parent_id', $menu_id, $parent_id); ?>
</p>

<p>
Type:<br>
<?php wpfm_selectarray('item_type', $mm_item_types, $item_type, "onChange=fnPopulate(this.form);", array('---Select Type---')); ?>
</p>


<p>
Link to:<br>
<input name="regexp" onKeyUp="myfilter.set(this.value)"  size="30"/>(Filter)<br />
<?php wpfm_selectlinks($item_type, $link_id); ?>
</p>
<p>
Target:<br>
<?php wpfm_selectarray('target', $mm_item_targets, $target, "", array(''=>'---Select Target---')); ?>
</p>


<p>
Class:<br>
<input name="class" type="text" size="30" value="<?php echo $class; ?>">
</p>

<p>
<input type="checkbox" name="published" value="1" <?php echo $published; ?> /> Published
</p>
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input type="hidden" name="op" value="">
<input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
<input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
<p class="submit">
<input type="submit" value="<?php echo $submit; ?>">
</p>
</form>
<script language="javascript" type="text/javascript">
var myfilter = new filterlist(document.frmNewMenuItem.link_id);
</script>
</div>

<?php
}


function wpfm_showmenulist(){
	global $wpdb;
	global $user_level, $wpfm_location;
	global $wpfm_table, $wpfm_table_items;
		
	$menus = $wpdb->get_results("SELECT * FROM $wpfm_table");
	if(!empty($menus)) {			
	?>	
	<div class="wrap"> 	
	<h2> Manage Menus </h2>
		<form name="frmUpdateMenu" action="<?php echo $wpfm_location; ?>" method="post" onSubmit="return confirm('Are you sure? \nThis will delete selected Items');">
		<?php if (isset($_GET['deleted'])) : ?>
	<div class="updated"><p><?php _e('Selected menu items deleted.') ?></p></div>
	<?php endif; ?>
	<?php if(!empty($menus)) { ?>  
	<table cellpadding="4" cellspacing="3" width="100%">
		<tr>
		  <th width="30" nowrap><?php _e('ID') ?></th>
			<th width="100%"><?php _e('Name') ?></th>
		  <th width="30" nowrap>&nbsp;</th>
		  <th width="10" nowrap>&nbsp;</th>
		</tr>
	<?php
	$style = '';
	foreach ($menus as $menu) {
		$style = ('class="alternate"' == $style) ? '' : 'class="alternate"';
	echo "\n<tr $style>
	<td>$menu->id</td>
	<td><a href=\"".$wpfm_location."&action=showitems&menu_id=$menu->id"."\">$menu->name</a></td>
	
	<td><a href=\"".$wpfm_location."&action=editmenu&menu_id=$menu->id"."\" class='edit'> Edit </a></td>
	
	<td><a href=\"".$wpfm_location."&action=delete&menu_id=$menu->id"."\" onClick=\"return confirm('This will delete the menu and all items')\" class='delete'>Delete</a></td>
	</tr>
	";
	}


?>	

	</table>
  </form>
		</div>
<?php		
	}
	}
}

function wpfm_showitems($menu_id){
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;

			
	?>	
	<div class="wrap"> 	
	<h2> Manage Menu Items </h2>
		<form name="frmUpdateMenuItems" action="<?php echo $wpfm_location; ?>" method="post" onSubmit="return confirm('Are you sure? \nThis will delete selected Items');">
		<?php if (isset($_GET['deleted'])) : ?>
	<div class="updated"><p><?php _e('Selected menu items deleted.') ?></p></div>
	<?php endif; ?>

	<table cellpadding="3" cellspacing="3" width="100%">
		<tr>
		  <th width="30" nowrap>&nbsp; </th>
			<th width="100%">Item Name</th>
		  <th nowrap>Published</th>
		  <th nowrap>Order</th>
		  <th nowrap>Type</th>
		  <th nowrap>ID Value</th>
		</tr>
	<?php
	
	if(!wpfm_showsubitems(0, $menu_id)) {
		echo "<tr><td colspan=\"6\">No Items found</td></tr>";	
	}



?>	

	</table>
	<p class="submit">
<input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
<input type="hidden" name="action" value="deleteitems">
<input type="submit" value="Delete">
</p>
  </form>
		</div>
<?php		

}

function wpfm_showsubitems($parent_id, $menu_id, $spacer = "", $sup = "") {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;
	static $style;
	
	$mm_item_types = apply_filters('mm_item_types', $mm_item_types);
	$mm_item_targets = apply_filters('mm_item_targets', $mm_item_targets);
			
	$items = $wpdb->get_results("SELECT * FROM $wpfm_table_items WHERE menu_id = '$menu_id' AND parent_id = '$parent_id' ORDER BY ordering ASC");
	
	if( !empty($items) ) {
		foreach ($items as $item) {
			$publish = ($item->published == 1) ? 0 : 1;
			$style = ('class="alternate"' == $style) ? '' : 'class="alternate"';
			echo "
			<tr $style>
				<td><input type=\"checkbox\" name=\"item_ids[]\" value=\"$item->id\" /></td>
				<td nowrap><a href=\"$wpfm_location&action=editmenuitem&item_id=$item->id\">$spacer $sup $item->name</a></td>
				<td align='center' nowrap>
				<a href=\"$wpfm_location&action=setpublish&published=$publish&item_id=$item->id&menu_id=$menu_id\">
				<img src=\"".get_option('siteurl')."/wp-content/plugins/menu-manager/images/publish_$item->published.png\" border=\"0\" />
				</a>
				</td>
				<td align='center' nowrap>
				<a href=\"$wpfm_location&action=setordering&order=up&item_id=$item->id&parent_id=$parent_id&menu_id=$menu_id\">
				<img src=\"".get_option('siteurl')."/wp-content/plugins/menu-manager/images/icon-up.png\" border=\"0\" />
				</a>
				&nbsp;&nbsp;&nbsp;
				<a href=\"$wpfm_location&action=setordering&order=down&item_id=$item->id&parent_id=$parent_id&menu_id=$menu_id\">
				<img src=\"".get_option('siteurl')."/wp-content/plugins/menu-manager/images/icon-down.png\" border=\"0\" />
				</a>
				</td>
				<td align='center' nowrap>".$mm_item_types[$item->item_type]."</td>
				<td align='center' nowrap>$item->link_id</td>
			</tr>";
			
			wpfm_showsubitems($item->id, $menu_id, $spacer."&nbsp;&nbsp;&nbsp;&nbsp;", "&#9492;");
			

		}
		return true;
	} else {
		return false;	
	}
}

function wpfm_parent($menu_id, $parent_id) {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;
	global $wpfm_list, $wpfm_level;
	
	$results = $wpdb->get_results("SELECT id, name FROM $wpfm_table_items WHERE menu_id = '$menu_id' AND parent_id = '$parent_id' ORDER BY ordering ASC");
	
	if(!empty($results)) {
		
		foreach ($results as $row) {
			$wpfm_list[$row->id] = str_repeat("- - ", $wpfm_level) . $row->name;
			$wpfm_level++;
			
			wpfm_parent($menu_id, $row->id);
			$wpfm_level--;
		}
		
	} else {
		
		return $wpfm_list;
		
	}
	
}

function wpfm_selectparent($name, $menu_id, $selected) {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items, $wpfm_level, $wpfm_list;
	
	$wpfm_level = 0;
	$wpfm_list[0] = "-----Top Level----";
	wpfm_parent($menu_id,0);
	
	
	
	echo "\n<select name=\"$name\" size=\"1\">";
	
	foreach ($wpfm_list as $key => $value) {
		
		$_selected = ($key == $selected)? ' selected' : '';
		echo "\n<option value=\"$key\" $_selected>$value</option>";
		
	}
	
	echo "\n</select>";
	
}

function wpfm_selectarray( $name, $array, $selected = '', $attributes = '', $extra_option = array(), $size = 1) {
	
	if(!empty($extra_option)) {
		$array = array_merge($extra_option, $array);	
	}
	
	echo "\n<select name=\"$name\" size=\"$size\" $attributes>";
	foreach ($array as $key => $value) {
		$_selected = ($selected == $key)? ' selected':'';
		echo "\n<option value = \"$key\" $_selected>$value</option>";
	}
	echo "</select>";
	
}

function wpfm_selectlinks($item_type, $selected) {
	global $wpfm_list_links, $wpfm_level;
	$wpfm_level = 0;
	if(empty($wpfm_list_links)) {
		$wpfm_list_links = array();
	}
	echo "<select name=\"link_id\" id=\"link_id\" size=15>";
	
	if($item_type != '') {
		
		switch ($item_type) {
			case 'category':
				wpfm_categories(0);
				break;
				
			case 'post':
				wpfm_posts();
				break;
				
			case 'page':
				wpfm_pages();
				break;
				
			case 'link':
				wpfm_links();
				break;
		
			default:
				break;
		}
		
		do_action('mm_list_links', $item_type);
		
	}
	foreach ($wpfm_list_links as $key => $value) {
		
		$_selected = ($key == $selected)? ' selected' : '';
		echo "\n<option value=\"$key\" $_selected>$value</option>";
		
	}
	
	echo "</select>";
	
}

function wpfm_categories($parent_id) {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;
	global $wpfm_list_links, $wpfm_level;
	
	$results = $wpdb->get_results("SELECT cat_ID as id, cat_name as name FROM $wpdb->categories WHERE category_parent = '$parent_id'");
	
	if(!empty($results)) {
		
		foreach ($results as $row) {
			$wpfm_list_links[$row->id] = str_repeat("- - ", $wpfm_level) . $row->name;
			$wpfm_level++;
			
			wpfm_categories($row->id);
			$wpfm_level--;
		}
		
	} else {
		
		return $wpfm_list_links;
		
	}
	
}


function wpfm_posts() {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;
	global $wpfm_list_links, $wpfm_level;
	
	$results = $wpdb->get_results("
	Select 
	posts.ID AS id,
	posts.post_title AS title,
	categories.cat_name AS category,
	count(posts.ID) 
	From
	$wpdb->post2cat AS post2cat
	Inner Join $wpdb->categories AS categories ON post2cat.category_id = categories.cat_ID
	Inner Join $wpdb->posts AS posts ON post2cat.post_id = posts.ID
	Where
	posts.post_status IN ('publish','draft')
	Group by
	posts.ID
	Having
	count(posts.ID) >=1
	");
	
	if(!empty($results)) {
		
		foreach ($results as $row) {
			
			$wpfm_list_links[$row->id] = $row->id . " : " . $row->category . " -> " . $row->title;
			
		}
		
	} 
	
}
function wpfm_links() {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;
	global $wpfm_list_links, $wpfm_level;
	
	$results = $wpdb->get_results("
	Select
	linkcategories.cat_name as category,
	links.link_id as id,
	links.link_name as title
	From
	$wpdb->linkcategories AS linkcategories
	Inner Join $wpdb->links AS links ON links.link_category = linkcategories.cat_id
	");
	
	if(!empty($results)) {
		
		foreach ($results as $row) {
			
			$wpfm_list_links[$row->id] = $row->id . " : " . $row->category . " -> " . $row->title;
			
		}
		
	} 
	
}


function wpfm_pages() {
	global $wpdb;
	global $user_level, $wpfm_location, $mm_item_types;
	global $wpfm_table, $wpfm_table_items;
	global $wpfm_list_links, $wpfm_level;
	
	$results = $wpdb->get_results("
	Select 
	posts.ID AS id,
	posts.post_title AS title
	From 
	$wpdb->posts AS posts 
	Where 
	posts.post_type = 'page'
	");
	
	if(!empty($results)) {
		
		foreach ($results as $row) {
			
			$wpfm_list_links[$row->id] = $row->id . " -> " . $row->title;
			
		}
		
	} 
	
}

function get_wpfm_link($link_id) {
	global $wpdb;
	
	$link = $wpdb->get_var("SELECT link_url FROM $wpdb->links WHERE link_id = '$link_id'");
	if(!empty($link)) {
		return $link;
	}
	return;
	
}



add_action('admin_menu', 'wpfm_add_management_page');


?>
