<?php

/*
Plugin Name: Auto Tags
Plugin URI: http://wordpress.org/extend/plugins/auto-tag/
Description: Tag posts on save an update from tagthe.net and yahoo services.
Version: 0.3.2
Author: Jonathan Foucher
Author URI: http://jfoucher.com

Copyright 2009 Jonathan Foucher

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

function sendToHost($host,$method,$path,$data,$useragent=0)
{
    // Supply a default method of GET if the one passed was empty
    if (empty($method)) {
        $method = 'GET';
    }
    $method = strtoupper($method);
    $fp = fsockopen($host, 80);
    if ($method == 'GET') {
        $path .= '?' . $data;
    }
    fputs($fp, $method." ".$path." HTTP/1.1\r\n");
    fputs($fp, "Host: $host\r\n");
    fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
    fputs($fp, "Content-length: " . strlen($data) . "\r\n");
    if ($useragent) {
        fputs($fp, "User-Agent: MSIE\r\n");
    }
    fputs($fp, "Connection: close\r\n\r\n");
    if ($method == 'POST') {
        fputs($fp, $data);
    }

    while (!feof($fp)) {
        $buf .= fgets($fp,128);
    }
    fclose($fp);
    return $buf;
}
function auto_get_yahoo_items($data) {
	ereg('<ResultSet([^>]*)>(.*)</ResultSet>',$data,$reg);
	//echo $data."<br />";
	//echo $reg[1];2],$re);
	return $reg[2];

}

function auto_get_yahoo_tags($data,$num){
	$data=trim(auto_get_yahoo_items($data));

	$data=str_replace(array('</Result>','<Result>'),array(',',''),$data);
	$motsarray=explode(',',$data);
	for ($i=0;$i<$num;$i++){
		$mot=trim ($motsarray[$i]);
		$mots.=$mot.',';
	}
	$data=substr($mots,0,-1);
	return $data;
	//echo $data;

}


function auto_get_items($data) {
	ereg('<dim type="topic">(.*)</dim>',$data,$reg);
	//echo $data."<br />";
	//echo $reg[1];
	ereg ('^(.*)</dim>',$reg[1],$re);
	return $re[1];

}

function auto_get_tags($data){
	$data=trim(auto_get_items($data));

	$data=str_replace(array('</item>','<item>'),array(',',''),$data);
	$motsarray=explode(',',$data);
	foreach ($motsarray as $mot){
		$mot=trim ($mot);
		$mots.=$mot.',';
	}
	$data=substr($mots,0,-2);
	return $data;
	//echo $data;
}

function auto_yahoo_tag($content,$num){
	$content="appid=BR_m.GrV34HyixkLbaEHmgSInktZjX1AohGCN6F6ywe5ojN01XGwDw4eRrV3rFdY8zdrhNWH&context=".urlencode(utf8_decode(strip_tags($content)));

	$data=sendToHost('api.search.yahoo.com','POST','/ContentAnalysisService/V1/termExtraction',$content,$useragent=0);
return auto_get_yahoo_tags($data,$num);
}

function auto_tag_the_net($content,$num){
	$content="text=".urlencode(utf8_decode(strip_tags($content)))."&count=".$num;

	$data=sendToHost('tagthe.net','POST','/api/',$content,$useragent=0);
	return auto_get_tags($data);
}



function autotag($postid){
	global $wp_filter;
	$yahoo_num=get_option('yahoo_num');
	$tagthenet_num=get_option('tagthenet_num');

	$post=get_post($postid, ARRAY_A);
	$content=$post['post_content'];

	$motclefs=auto_tag_the_net($content,$tagthenet_num).",".auto_yahoo_tag($content,$yahoo_num);

	remove_action('wp_insert_post','autotag');
	wp_set_post_tags( $postid,$motclefs, true );
}


function initAutotagPlugin()  
{  

    $yahoo_num = get_option( 'yahoo_num');
	$tagthenet_num = get_option( 'tagthenet_num');
    if( $_POST['sent'] == 'Y' ) {
        $tagthenet_num = $_POST[ 'tagthenet_num' ];
		$yahoo_num = $_POST[ 'yahoo_num' ];
        update_option( 'yahoo_num', $yahoo_num );
		update_option( 'tagthenet_num', $tagthenet_num );
?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }
    echo '<div class="wrap">';
    echo "<h2>" . __( 'Autotags Options' ) . "</h2>";
    ?>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="sent" value="Y">

<p><?php _e("Number of tags from Yahoo:", 'mt_trans_domain' ); ?> 
<input type="text" name="yahoo_num" value="<?php echo $yahoo_num; ?>" size="20">
</p>
<p><?php _e("Number of tags from tagthe.net:", 'mt_trans_domain' ); ?> 
<input type="text" name="tagthenet_num" value="<?php echo $tagthenet_num; ?>" size="20">
</p>
<hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p>

</form>
<p>Hey! Do you like this plugin? Give it a <a href="http://wordpress.org/extend/plugins/auto-tag">good rating</a> on wordpress, or blog about it!</p>
</div>

	<?php  


}  
function addPluginToSubmenu()   
{  
	add_submenu_page('options-general.php', 'Auto tags plugin', 'Auto tags plugin', 10, __FILE__, 'initAutotagPlugin');  
}  
add_action('admin_menu', 'addPluginToSubmenu');  

add_action('wp_insert_post','autotag',1);
?>
