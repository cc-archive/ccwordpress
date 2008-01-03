<?php

class SU_Database
{
	function upgrade ($old, $new)
	{
		$this->install ();
			
		update_option ('search_unleashed_version', $new);
	}
	
	function install ()
	{
		global $wpdb;

		$sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}search (
		  `post_id` int(11) unsigned NOT NULL,
		  `comment_id` int(10) unsigned NOT NULL default '0',
		  `content` text,
		  `priority` text,
		  PRIMARY KEY  (`post_id`,`comment_id`),
	  	FULLTEXT KEY `content` (`content`,`priority`)
		) ENGINE=MyISAM CHARSET=utf8";
		
		$sql[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}search_phrases (
		  `id` int(11) unsigned NOT NULL auto_increment,
		  `phrase` varchar(100) NOT NULL default '',
		  `ip` int(10) unsigned NOT NULL,
		  `searched_at` datetime NOT NULL,
		  `referrer` varchar(100) default NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM CHARSET=utf8";
		
		if (version_compare (mysql_get_server_info (), '4.0.18', '<'))
		{
			foreach ($sql AS $pos => $line)
				$sql[$pos] = str_replace ('ENGINE=MyISAM ', '', $line);
		}
		
		foreach ($sql AS $pos => $line)
			$wpdb->query ($line);
	}

	function remove ()
	{
		global $wpdb;

		$wpdb->query ("DROP TABLE {$wpdb->prefix}search");
		$wpdb->query ("DROP TABLE {$wpdb->prefix}search_phrases");
	}
}
?>
