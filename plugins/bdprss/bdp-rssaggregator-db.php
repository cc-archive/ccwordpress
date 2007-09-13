<?php 

if( !class_exists('BDPRSS_DB') ) {

	class BDPRSS_DB 
	{	
		var $sitetable;
		var $cfeedurl, $csitename, $cdescription, $csiteurl, $clastpolltime, $curlindex, 
			$cnextpolltime, $cgmtadjust, $cupdatetime, $cidentifier,
			$csitenameoverride, $cpollingfreqmins;

		var $itemtable;
		var $iidentifier, $ifeedidentifier, $iitemname, $iitemtext, 
			$iitemurl, $iitemtime, $iitemdate;

		var $listtable;
		var $lidentifier, $lname, $ltype, $lshort, $lage, $lurls, $litemspersite, $llistall,
			$lprefeed, $lpostfeed, $lpresite, $lpostsite, $lpresitename, $lpostsitename,
			$lpreitemset, $lpostitemset, $lpreitem, $lpostitem, $lpreitemname, $lpostitemname,
			$lpreitembody, $lpostitembody, $lpreitemage, $lpostitemage, $lbetwixtsiteitem,
			$lmaxlength, $loutputsitename, $llaunchnew, $lallowablexhtmltags, $lmaxwordlength,
			$lprearchivelist, $lpostarchivelist, $lprearchivedate, $lpostarchivedate,
			$larchivedateformat, $larchiveweeks, $larchiveviewpage, 
			$liscachable, $lcacheviewpage,
			$lprecacheitem, $lpostcacheitem, $lprecachetitle, $lpostcachetitle,
			$lprecachesite, $lpostcachesite, $lprecacheitemtime, $lpostcacheitemtime,
			$lprecacheupdatetime, $lpostcacheupdatetime,
			$lcanrss, $lrssnumbersingle, $lrssfullorsummary, $lrssdescription, $lrsslink;
		
		var $errortable;
		var $eidentifier, $efeedurl, $etime, $etext;
		
		var $now;

		function BDPRSS_DB() 
		{
			/* BDPRSS_DB() - initialisition function that sets constant names for later use */

			global $wpdb,  $table_prefix;
			
			$this->now = time();
			
			// --- site-table
			$this->sitetable =			$table_prefix.'bdprss_sites_v3';
			$this->cidentifier = 		'identifier';			// primary key
			$this->cfeedurl = 			'feed_url';				// indexed
			$this->csitename =			'site_name';
			$this->csitenameoverride =	'site_name_overriden';	// manual override for site feed names
			$this->cdescription = 		'description';
			$this->csiteurl =			'site_url';
			$this->clastpolltime = 		'last_poll_time';		// time last polled
			$this->cnextpolltime = 		'next_poll_time';		// next scheduked poll
			$this->cpollingfreqmins =	'polling_freq_in_mins';	// adjustable polling frequency
			$this->cupdatetime = 		'site_update_time';		// time last updated
			$this->cgmtadjust = 		'gmt_adjustment';		// GMT adjustment to pubDate
			
			// --- item-table
			$this->itemtable = 			$table_prefix."bdprss_items_v3";
			$this->iidentifier = 		"identifier";			// primary key
			$this->ifeedidentifier =	"item_feed_id";			// ( combined unique key
			$this->iitemurl = 			"item_url";				// ( combined unique key
			$this->iitemname =			"item_name";
			$this->iitemtext =			"text_body";
			$this->iitemtime = 			"item_time";			// item pubDate time
			$this->iitemdate = 			"item_date";			// item date
			$this->iitemupdate = 		"item_update_time";		// item last updated time -- for debugging
			
			// --- list-table
			$this->listtable = 			$table_prefix."bdprss_lists_v3";
			// about the list
			$this->lidentifier = 		"identifier";			// primary key
			$this->lname = 				"name";					// a useful handle
			$this->ltype = 				"type";					// type of list: 
			// list contents
			$this->lurls =				"url_list";				// comma separate list
			$this->llistall =			"list_all";				// list all url ids
			// list style
			$this->litemspersite =		"items_per_site";		//
			$this->llaunchnew =			"launch_in_new_window";	// launch links in a new window
			$this->lshort =				"output_item_heading_only";
			$this->lmaxlength = 		"max_words_in_synposis";// 0 = no limit
			$this->lmaxwordlength =		"max_word_length";
			$this->lage =				"output_age";
			$this->loutputsitename =	"output_site_name"; 
			$this->lallowablexhtmltags=	"allowable_xhtml_tags";
			// list formating
			$this->lprefeed = 			"pre_feed";
			$this->lpostfeed = 			"post_feed";
			$this->lpresite =			"pre_site";
			$this->lpostsite =			"post_site";
			$this->lpresitename = 		"pre_site_name";
			$this->lpostsitename = 		"post_site_name";
			$this->lpreitemset =		"pre_item_set";
			$this->lpostitemset =		"post_item_set";
			$this->lpreitem =			"pre_item";
			$this->lpostitem =			"post_item";
			$this->lpreitemname =		"pre_item_name";
			$this->lpostitemname =		"post_item_name";
			$this->lbetwixtsiteitem = 	"between_site_and_item_name";
			$this->lpreitemage =		"pre_item_age";
			$this->lpostitemage =		"post_item_age";
			$this->lpreitembody =		"pre_item_body";
			$this->lpostitembody =		"post_item_body";
			// Archiving
			$this->larchiveviewpage =	"archive_view_page";
			$this->lprearchivelist = 	"pre_archive_list";
			$this->lpostarchivelist = 	"post_archive_list";
			$this->lprearchivedate = 	"pre_archive_date";
			$this->lpostarchivedate = 	"post_archive_date";
			// Cache
			$this->liscachable =			"enable_caching";
			$this->lcacheviewpage =			"cache_view_page";
			$this->lprecacheitem =			"pre_cache_item";
			$this->lpostcacheitem =			"post_cache_item";
			$this->lprecachetitle =			"pre_cache_title";
			$this->lpostcachetitle =		"post_cache_title";
			$this->lprecachesite =			"pre_cache_site";
			$this->lpostcachesite =			"post_cache_site";
			$this->lprecacheitemtime =		"pre_cache_item_time";
			$this->lpostcacheitemtime =		"post_cache_item_time";
			$this->lprecacheupdatetime =	"pre_cache_update_time";
			$this->lpostcacheupdatetime =	"post_cache_update_time";
			// RSS
			$this->lcanrss =				"can_rss_this_list";
			$this->lrssnumbersingle =		"num_posts_single_channel_rss_stream";
			$this->lrssdescription =		"description_of_aggregated_feed";
			$this->lrsslink =				"link_for_aggregated_feed";
			$this->lrssfullorsummary =		"rss_text_provided_in_full_or_summary";
			
			// --- error-table
			$this->errortable =			$table_prefix.'bdprss_errors_v3';
			$this->eidentifier =		'identifier';
			$this->efeedurl =			'feed_url';
			$this->etime =				'when_it_happened';
			$this->etext = 				'error_text';
		} // function BDPRSS_DB
		
		
		/* --- create --- */

		function db_exists()
		{
			global $wpdb;
			return ($wpdb->get_var("show tables like '$this->itemtable'") == $this->itemtable);
		}

		function create() 
		{
			/* create() - create the database tables if they do not already exist */
			
			global $wpdb;
			
			$charset = '';
			switch(get_option('blog_charset'))
			{
				case 'UTF-8':
				case 'utf-8':
					$charset = ' CHARSET=utf8';
					break;
			}
			//$charset = '';
			
			$sql =  "CREATE TABLE IF NOT EXISTS $this->listtable (".
				// about the list
				"$this->lidentifier			int(10) NOT NULL auto_increment, " .
				"$this->lname				varchar(255) NOT NULL default '-', ".
				"$this->ltype			enum('countrecentitem','daterecentitem','sitealpha','siteupdate') ".
											"NOT NULL default 'countrecentitem', " .
				// list contents
				"$this->lurls				text, ".
				"$this->llistall			enum('Y','N') NOT NULL default 'Y', " .
				// list style
				"$this->litemspersite		int(4) NOT NULL DEFAULT '20', ".
				"$this->llaunchnew			enum('Y','N') NOT NULL default 'Y', " . 
				"$this->lshort				enum('Y','N') NOT NULL default 'N', " .
				"$this->lmaxlength			int(4) NOT NULL DEFAULT '60', ".
				"$this->lmaxwordlength		int(4) NOT NULL DEFAULT '35', ".
				"$this->lage				enum('Y','N') NOT NULL default 'Y', " .
				"$this->loutputsitename		enum('Y','N') NOT NULL default 'Y', " .
				"$this->lallowablexhtmltags	varchar(100) default '', ".
				// list XHTML formatting
				"$this->lprefeed 			varchar(100) DEFAULT '', ".
				"$this->lpostfeed			varchar(100) default '', ".
				"$this->lpresite			varchar(100) default '', ".
				"$this->lpostsite			varchar(100) default '', ".
				"$this->lpresitename		varchar(100) default '', ".
				"$this->lpostsitename		varchar(100) default '', ".
				"$this->lpreitemset 		varchar(100) default '', ".
				"$this->lpostitemset		varchar(100) default '', ".
				"$this->lpreitem			varchar(100) default '', ".
				"$this->lpostitem			varchar(100) default '', ".
				"$this->lpreitemname		varchar(100) default '<h3>', ".
				"$this->lpostitemname		varchar(100) default '</h3>', ".
				"$this->lpreitemage			varchar(100) default '<p class=\"postbyline\">', ".
				"$this->lpostitemage		varchar(100) default '</p>', ".
				"$this->lpreitembody		varchar(100) default '<p>', ".
				"$this->lpostitembody		varchar(100) default '</p>', ".
				"$this->lbetwixtsiteitem 	varchar(100) default ' &raquo;&nbsp;', ".
				// Archive
				"$this->larchiveviewpage	varchar(200) default '', ".
				"$this->lprearchivelist		varchar(100) default '', ".
				"$this->lpostarchivelist	varchar(100) default '', ".
				"$this->lprearchivedate		varchar(100) default '', ".
				"$this->lpostarchivedate	varchar(100) default ' &nbsp; ', ".
				// Cache
				"$this->liscachable				enum('Y','N') NOT NULL default 'N', " .
				"$this->lcacheviewpage			varchar(200) default '', ".
				"$this->lprecacheitem			varchar(100) default '', ".
				"$this->lpostcacheitem 			varchar(100) default '', ".
				"$this->lprecachetitle			varchar(100) default '<h3>', ".
				"$this->lpostcachetitle			varchar(100) default '</h3>', ".
				"$this->lprecachesite	varchar(100) default '<p class=\"postbyline\"><em> From:&nbsp;', ".
				"$this->lpostcachesite 			varchar(100) default '<br />', ".
				"$this->lprecacheitemtime		varchar(100) default 'Posted:&nbsp;', ".
				"$this->lpostcacheitemtime		varchar(100) default ' (approx)<br />', ".
				"$this->lprecacheupdatetime		varchar(100) default 'RSS cached:&nbsp;', ".
				"$this->lpostcacheupdatetime	varchar(100) default '</em></p>', ".
				// RSS
				"$this->lcanrss					enum('Y','N') NOT NULL default 'N', " .
				"$this->lrssfullorsummary		enum('FULL','SUMMARY') NOT NULL default 'SUMMARY', " .
				"$this->lrssnumbersingle		int(5) NOT NULL DEFAULT '50', ".
				"$this->lrsslink				varchar(200) default '".get_option('siteurl')."', ".
				"$this->lrssdescription			varchar(100) default 'An aggregated feed', ".
				// Keys
				"PRIMARY KEY 				($this->lidentifier) ) $charset"; 
			$result = $wpdb->query($sql);
			
			$sql = "CREATE TABLE IF NOT EXISTS $this->errortable (".
				"$this->eidentifier			int(10) NOT NULL auto_increment, ".
				"$this->efeedurl			varchar(240) NOT NULL, ".
				"$this->etime				int(15) NOT NULL, ".
				"$this->etext				text NOT NULL, ".
				"PRIMARY KEY				($this->eidentifier), ".
				"INDEX						($this->efeedurl) ) $charset";
			$result = $wpdb->query($sql);

			$sql = "CREATE TABLE IF NOT EXISTS $this->sitetable (".
				"$this->cidentifier			int(10) NOT NULL auto_increment, " .
				"$this->cfeedurl			varchar(240) NOT NULL, ".
				"$this->csitename			varchar(255), ".
				"$this->csitenameoverride	enum('Y','N') NOT NULL default 'N', " .
				"$this->cdescription		varchar(255), ".
				"$this->csiteurl			varchar(255), ".
				"$this->clastpolltime		int(15) NOT NULL DEFAULT 1, " .
				"$this->cnextpolltime		int(15) NOT NULL DEFAULT 1, " .
				"$this->cupdatetime			int(15) NOT NULL DEFAULT 1, " .
				"$this->cgmtadjust			float(4,1) NOT NULL DEFAULT 0.0, ".
				"$this->cpollingfreqmins	int(6) NOT NULL DEFAULT 0, ".
				"PRIMARY KEY				($this->cidentifier), ".
				"UNIQUE KEY					($this->cfeedurl), ".
				"INDEX						($this->clastpolltime) ) $charset";
			$result = $wpdb->query($sql);

			$sql = "CREATE TABLE IF NOT EXISTS $this->itemtable (".
				"$this->iidentifier			int(10) NOT NULL auto_increment, ".
				"$this->ifeedidentifier		int(10) NOT NULL, ".
				"$this->iitemurl			varchar(250) NOT NULL, ".
				"$this->iitemname			varchar(255) NOT NULL, ".
				"$this->iitemtext			text NOT NULL, ".
				"$this->iitemtime			int(15) NOT NULL, ".
				"$this->iitemdate			date NOT NULL, ".
				"$this->iitemupdate			int(15) NOT NULL, ".
				"PRIMARY KEY				($this->iidentifier), " .
				"UNIQUE KEY					($this->ifeedidentifier, $this->iitemurl), ".
				"INDEX						($this->iitemdate), ".
				"INDEX						($this->ifeedidentifier), ".
				"INDEX						($this->iitemupdate), ".
				"INDEX						($this->iitemtime) ) $charset";
			$result = $wpdb->query($sql);
		}
		
		/* --- insert --- */ 
		function recordError($url, $text)
		{
			global $wpdb;
			
			if(!$url) return;
			
			// some SQL insertion protection using HTML entities
			$text = preg_replace("/'/", '&#39;', $text);
			$text = preg_replace('/"/', '&quot;', $text);
			
			if(!$text) $text = 'Unknown';
			
			$sql = "INSERT INTO $this->errortable ($this->efeedurl, $this->etime, $this->etext) ".
				"VALUES ('$url', '$this->now', '$text')";
			$result = $wpdb->query($sql);
		}

		function createlist() 
		{
			/* createlist() -- inserts an empty output format into the list table */
			global $wpdb;
			
			$sql = "INSERT INTO $this->listtable ($this->lname) ".
				"VALUES ('New format: please give it a meaningful name')";
			$result = $wpdb->query($sql);
			return mysql_insert_id();
		}
		
		function insert_in_sitetable($url, $polltime) 
		{
			global $wpdb;
			
			$sql = "INSERT INTO $this->sitetable ". 
				"($this->cfeedurl, $this->clastpolltime) ". 
				"VALUES ('$url', '$polltime')"; 
			$result = $wpdb->query($sql);
		}
		
		
		/* --- insert or modify --- */
		
		function updateItem($feedID, $title, $counter, $text, $link, $ticks) 
		{
			/* updateItem($feedID, $title, $counter, $text, $link, $ticks)
			*	-- this function either inserts or updates the item into the itemtable
			*	-- only enters the time ($ticks) into the database for an insert - we ignore $ticks for updates
			* 	-- returns false on update and true on insert
			*/
			
			global $wpdb;
			$sql = "SELECT COUNT(*) FROM $this->itemtable ".
					"WHERE $this->ifeedidentifier='$feedID' AND $this->iitemurl='$link'";
			$count = $wpdb->get_var($sql);

			if($count>0)
			{
				$sql = "UPDATE $this->itemtable ".
					"SET $this->iitemupdate='".$this->now."', ".
					"$this->iitemtext='$text', ".
					"$this->iitemname='$title' ".
					"WHERE $this->ifeedidentifier='$feedID' AND $this->iitemurl='$link' ";
				$result = $wpdb->query($sql);
				return FALSE;
			}
			
			$dateStamp = date('Y-m-d', $ticks);
			
			$sql = "INSERT INTO $this->itemtable ($this->ifeedidentifier, $this->iitemurl, $this->iitemname, ".
				"$this->iitemtext, $this->iitemupdate, $this->iitemtime, $this->iitemdate)".
				" VALUES ('$feedID', '$link', '$title', '$text', '$this->now', '$ticks', '$dateStamp') ";
			$result = $wpdb->query($sql);
			return TRUE;
		}
		
		/* --- modify --- */
		
		function updateTable($tableName, &$valueArray, $identifier, $specialCase=FALSE)
		{
			global $wpdb;
			
			if(!isset($valueArray[$identifier]) || !$valueArray[$identifier])
			{
					$this->recordError('SNARK', 
						"BDPRSS_DB::updateTable() missing identifier ($identifier)".
						"in valueArray for $tableName -- this should never happen");
					return FALSE;
			}
			
			if($specialCase && $tableName==$this->sitetable)
			{
				if(isset($valueArray['csitename']))		unset($valueArray['csitename']);
				if(isset($valueArray['csiteurl']))		unset($valueArray['csiteurl']);
				if(isset($valueArray['cdescription']))	unset($valueArray['cdescription']);
			}
			
			$sql = "UPDATE $tableName SET ";
			foreach($valueArray as $key => $value)
			{
				if($key==$identifier) continue;
				$value = preg_replace('/"/',	'&quot;', 	$value);
				$value = preg_replace("/'/",	'&#39;', 	$value);
				$sql .= $this->{$key}."='$value', ";
			}
			$sql = preg_replace('/^(.*), $/', '\\1', $sql);
			$sql .= " WHERE ".$this->{$identifier}."='".$valueArray[$identifier]."' ";
			$result = $wpdb->query($sql);
			return $result;
		}
		
		function updateOldest() 
		{
			global $wpdb;
			
			// at most we only want to impose the burden of updating one feed on this site user
			$sql = "SELECT MIN($this->cnextpolltime) FROM $this->sitetable ";
			$mini = $wpdb->get_var($sql);
			if(!$mini || $mini > $this->now) return;
			
			$sql =  "SELECT * FROM  $this->sitetable WHERE $this->cnextpolltime='$mini' LIMIT 1";
			$site = $wpdb->get_row($sql);
			if(!$site) return;
			
			BDPRSS2::update($site);
		}
		
		function updateAll() 
		{
			global $wpdb;
			
			$sql = 	"SELECT * FROM $this->sitetable ";
			$sites = $wpdb->get_results($sql);
			
			if($sites) 
				foreach($sites as $site) 
					BDPRSS2::update($site);
		} 
		
		
		/* --- retrieve --- */
		
		function get_mysql_version() 
		{
			global $wpdb;
			
			$sql = "SELECT version()";
			$result = $wpdb->get_var($sql);
			
			return $result;
		}
		
		function is_in_sitetable($url) 
		{
			global $wpdb;
			
			$sql = 	"SELECT * FROM $this->sitetable ".
				"WHERE $this->cfeedurl='$url' LIMIT 1";
			$result = $wpdb->get_row($sql);
			
			if($result && $result->{$this->cfeedurl} == $url) return TRUE;
			return FALSE;
		}
		
		function count_in_sitetable() 
		{
			global $wpdb;
			
			$sql = 	"SELECT COUNT(*) FROM $this->sitetable ";
			
			$result = $wpdb->get_var($sql);
			
			return $result;
		}
		
		function countErrors($url='') 
		{
			global $wpdb;
			
			$sql = 	"SELECT COUNT(*) FROM $this->errortable";
			if($url) $sql .= " WHERE $this->efeedurl='$url'";
			$result = $wpdb->get_var($sql);
			
			return $result;
		}
		
		function getErrors($url='') 
		{
			global $wpdb;
			
			$sql = 	"SELECT * FROM $this->errortable";
			if($url) $sql .= " WHERE $this->efeedurl='$url'";
			$sql .= " ORDER BY $this->efeedurl, $this->eidentifier";
			$result = $wpdb->get_results($sql);

			return $result;
		}

		function count_in_listtable() 
		{
			global $wpdb;

			$sql = 	"SELECT COUNT(*) FROM $this->listtable ";
			
			$result = $wpdb->get_var($sql);

			return $result;
		}

//		function is_in_itemtable($url) 
//		{
//			global $wpdb;
//
//			$sql = 	"SELECT * FROM $this->itemtable ".
//				"WHERE $this->ifeedurl='$url' LIMIT 1";
//			$result = $wpdb->get_row($sql);
//
//			if($result && $result->{$this->ifeedurl} == $url) return TRUE;
//			return FALSE;
//		}
		
		function get_all_lists() 
		{
			global $wpdb;
			$sql = "SELECT * FROM $this->listtable ".
				"ORDER BY $this->lidentifier ";
			$result = $wpdb->get_results($sql);
			return $result;
		}
		
		function get_all_sites($ltype='sitealpha') 
		{
			global $wpdb;
			$sql = "SELECT * FROM $this->sitetable ";
			
			if($ltype == 'sitealpha')
				$sql .= "ORDER BY $this->csitename ";
			elseif($ltype == 'siteupdate')
				$sql .= "ORDER BY $this->cupdatetime DESC";
			
			$result = $wpdb->get_results($sql);
			return $result;
		}
		
		function get_site($url) 
		{
			global $wpdb;
			$sql = "SELECT * FROM $this->sitetable WHERE $this->cfeedurl='$url' ";
			$result = $wpdb->get_row($sql);
			return $result;
		}
		
		function get_site_by_id($id) 
		{
			global $wpdb;
			$sql = "SELECT * FROM $this->sitetable WHERE $this->cidentifier='$id' ";
			$result = $wpdb->get_row($sql);
			return $result;
		}
		
		function get_feedurl_from_site_id($id) 
		{
			global $wpdb;
			$sql = "SELECT $this->cfeedurl FROM $this->sitetable WHERE $this->cidentifier='$id' ";
			$result = $wpdb->get_row($sql);
			if(!$result) return FALSE;
			return $result->{$this->cfeedurl};
		}
		
		function get_siteurl_from_site_id($id) 
		{
			global $wpdb;
			$sql = "SELECT $this->csiteurl FROM $this->sitetable WHERE $this->cidentifier='$id' ";
			$result = $wpdb->get_row($sql);
			if(!$result) return FALSE;
			return $result->{$this->csiteurl};
		}
		
		function get_list($list_id) 
		{
			global $wpdb;
			$sql = "SELECT * FROM $this->listtable WHERE $this->lidentifier='$list_id' ";
			$result = $wpdb->get_row($sql);
			return $result;
		}
		
//		function get_item($feedurl, $itemurl) 
//		{
//			global $wpdb;
//			$sql = 	"SELECT * FROM $this->itemtable ".
//				"WHERE $this->ifeedurl='$feedurl' AND $this->iitemurl='$itemurl' ";
//			$result = $wpdb->get_row($sql);
//			return $result;
//		}
		
		function getItemByID($id) 
		{
			global $wpdb;
			$sql = 	"SELECT * FROM $this->itemtable WHERE $this->iidentifier='$id' ";
			$result = $wpdb->get_row($sql);
			return $result;
		}
		
		function get_most_recent_item_time($feedID) 
		{
			global $wpdb;
			$sql = 	"SELECT MAX($this->iitemtime) FROM $this->itemtable ".
				"WHERE $this->ifeedidentifier='$feedID' ";
			$result = $wpdb->get_var($sql);
			return $result;
		}
		
		function getArchiveList($since, $mode, $ids=false)
		{
			global $wpdb;
			
			$sql = "SELECT $this->iitemdate FROM $this->itemtable ".
				"WHERE $this->iitemtime>'$since' ";
			if($ids) 
			{
				$virgin = true;
				foreach($ids as $id) 
				{
					if(!$id) continue;
					if($virgin)
						$sql .= "AND ( ";
					else
						$sql .= "OR";
					$sql .= " $this->ifeedidentifier='$id' ";
					$virgin = false;
				}
				if(!$virgin) $sql .= ") ";
			}			
			
			if($mode == 'month')
				$sql .= "Group By Month($this->iitemdate), Year($this->iitemdate) "; 
			else
				$sql .= "Group By $this->iitemdate ";

			$sql .= "ORDER BY $this->iitemdate DESC ";
			
			$result = $wpdb->get_results($sql);
			return $result;
		}
		
		function getItems($feedID=false, $max=0, $ids=false, $itemdate=false) 
		{
			global $wpdb;
			
			$sql = "SELECT * FROM $this->itemtable ";
			$where = 'WHERE ';
			if($itemdate) 
			{
				if(ereg('[0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]', $itemdate))
				{
					$sql .= "WHERE $this->iitemdate='$itemdate' ";
				}
				else
				{
					list($year, $month) = split('-', $itemdate);
					$sql .= "WHERE Month($this->iitemdate)='$month' AND Year($this->iitemdate)='$year' ";
				}
				$where = 'AND ';
			}
			if($feedID) 
			{
				$sql .= "$where $this->ifeedidentifier='$feedID' ";
				$where = 'AND ';
			}
			if($ids) 
			{
				$virgin = true;
				foreach($ids as $id) 
				{
					if(!$id) continue;
					if($virgin)
						$sql .= "$where ( ";
					else
						$sql .= "OR";
					$sql .= " $this->ifeedidentifier='$id' ";
					$virgin = false;
				}
				if(!$virgin) $sql .= ") ";
				//$where = 'AND ';
			}
			$sql .= "ORDER BY $this->iitemtime DESC ";
			if($max) $sql .= "LIMIT $max ";
			
			$result = $wpdb->get_results($sql);
			return $result;
		}
		
		
		/* --- delete --- */
		
		function deleteFeed($feedID) 
		{
			global $wpdb;
			
			$url = $this->get_feedurl_from_site_id($feedID);
			
			if(!$url) return;
			
			$sql = "DELETE FROM $this->sitetable WHERE $this->cidentifier='$feedID'";
			$result = $wpdb->query($sql);
			
			$sql = "DELETE FROM $this->itemtable WHERE $this->ifeedidentifier='$feedID'";
			$result = $wpdb->query($sql);
			
			$sql = "DELETE FROM $this->errortable WHERE $this->efeedurl='$url'";
			$result = $wpdb->query($sql);
		}
		
		function deletelist($list) 
		{
			global $wpdb;
			
			$sql = "DELETE FROM $this->listtable WHERE $this->lidentifier='$list'";
			$result = $wpdb->query($sql);
		}
		
		function deleteErrors($url)
		{
			global $wpdb;
			
			$sql = "DELETE FROM $this->errortable ".
				"WHERE $this->efeedurl='$url' ";
			$result = $wpdb->query($sql);
			
			return $reult;
		}
		
		function deleteErrorTable()
		{
			global $wpdb;
			
			$sql = "DROP TABLE IF EXISTS $this->errortable";
			$result = $wpdb->query($sql);
			
			$this->create();
		}
		
		function delete_old_items($feedID) 
		{
		/* delete_old_items($url)
		 */
			global $wpdb;
			
			$oldDefined = (int) get_option('bdprss_keep_howlong');
			if(!$oldDefined) return;
			$oldDefined *= 60 * 60 * 24 * 31; // seconds in a month
			$oldDefined = $this->now - $oldDefined;
			
			$sql = "DELETE FROM $this->itemtable ".
				"WHERE $this->ifeedidentifier='$feedID' ".
				"AND ($this->iitemtime<'$oldDefined' OR $this->iitemtime='')";
			$result = $wpdb->query($sql);
			
			return $reult;
		}
		
		function reset() 
		{
		/* reset() -- delete the database tables - 
		 *		god knows why you would do this -- 
		 *		this is the button of death
		 */
			global $wpdb, $table_prefix;
			
			$tablenames = array(
				$this->errortable, $this->listtable, $this->itemtable, $this->sitetable
			);
			
			foreach($tablenames as $t)
			{
				$sql = "DROP TABLE IF EXISTS $t ";
				$result = $wpdb->query($sql);
			}
			
			$this->create();
		}
	} // class
}//if

// Make a singleton global instance.
if ( !isset($bdprss_db) ) $bdprss_db = new BDPRSS_DB();

?>