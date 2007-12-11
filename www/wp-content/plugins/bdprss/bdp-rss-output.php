<?php

if( !class_exists('BDPRSSOUTPUT') ) 
{
	class BDPRSSOUTPUT
	{
		function packageItemText($string, $wordCount=0, $maxWordLength=50, $processTags=FALSE, $tagSet='')
		{
			// keep acceptable tags
			$string = eregi_replace("\&lt;", 	'<', 	$string);
			$string = eregi_replace("\&gt;", 	'>', 	$string);
			if($processTags && $tagSet)
			{
				$tagSet = preg_split("','", $tagSet, -1, PREG_SPLIT_NO_EMPTY);
				foreach($tagSet as $ts)
				{
					// space out tags so they are are easy to identify
					$string = eregi_replace("<($ts [^>]*)>",	" &lt;\\1&gt;",	$string);
					$string = eregi_replace("<($ts)>",		" &lt;\\1&gt;",	$string);
					$string = eregi_replace("<(/$ts)>",		" &lt;\\1&gt;",	$string);
				}
			}
			// delete unrequired tags
			$string = eregi_replace("<[a-zA-Z]+[^>]*>", 	'',	$string);
			$string = eregi_replace("</[a-zA-Z]+[^>]*>",	'',	$string);
			// restore required tags
			$string = eregi_replace("\&lt;", 	'<', 	$string);
			$string = eregi_replace("\&gt;", 	'>', 	$string);
				
			// count words
			$words = explode(' ', $string);
			$outWords = array();
			$count = count($words);
			
			$inTag = false;
			$HTMLclosure = array();
			$token = false;
			
			if(!$wordCount) $wordCount= -1;	// backward compatibility so that zero = no limit
			
			for($i=0; $i<$count && $wordCount!=0; $i++)
			{
			    	$outWords[$i] = trim ($words[$i]);
	
				if(!$outWords[$i]) continue;
			
				if($processTags)
				{
					if(ereg('^<', $outWords[$i])) 
					{
						if($inTag) echo "<!-- glitch nested tags? -->\n";
						$inTag = TRUE;
						if(ereg('^<([a-zA-Z]+)', $outWords[$i], $matches))
						{
							//open tag
							$m = strtolower($matches[1]);
							array_push($HTMLclosure, $m); 
							$token = $m;
						}
						if(ereg('^</([a-zA-Z]+).*', $outWords[$i], $matches))
						{
							// close tag
							$m = strtolower($matches[1]);
							$t = array_pop($HTMLclosure);
							if($t && $t!=$m)
								array_push($HTMLclosure, $t);
							$inTag = FALSE;
						}
					}
					
					if($inTag)
					{
						if(ereg('/>', $outWords[$i]))
						{
							// closure
							$m = $token;
							$t = array_pop($HTMLclosure);
							if($t && $t!=$m) array_push($HTMLclosure, $t);
						}
						// quotes in tags must be respected
						$outWords[$i] = eregi_replace('&quot;',	'"', 	$outWords[$i]);
						$outWords[$i] = eregi_replace('&#39;', 	"'",  	$outWords[$i]);
						if(ereg('>', $outWords[$i]))  
						{					
							$inTag = FALSE;
							$token = FALSE;
						}
						continue;
					}
				}
				$len = strlen($outWords[$i]);
				if($maxWordLength && $len > $maxWordLength)
				{ 
					$outWords[$i] =  substr($outWords[$i], 0, $maxWordLength);
					$outWords[$i] .= '~';
				}
				$wordCount--;
			}
			
			$ret =  implode(' ', $outWords);
			
			if($inTag) $ret .= '>';
			
			if(count($words) > count($outWords)) $ret .= ' ...';
			
			if($processTags)
			{
				// close open tags
				while($t = array_pop($HTMLclosure)) $ret .= "</$t>"; 
				
				// tighten up the HTML 
				$ret = eregi_replace(" (</[a-zA-Z]+>)", "\\1", $ret);
				$ret = eregi_replace("([\(\$\[\{]) (<[a-zA-Z]+[^\>]*>)", "\\1\\2", $ret);
				$ret = eregi_replace("&quot; (<[a-zA-Z]+[^\>]*>)", "&quot;\\1", $ret);
				$ret = eregi_replace("&#34; (<[a-zA-Z]+[^\>]*>)", "&#34;\\1", $ret);
				$ret = eregi_replace("&#39; (<[a-zA-Z]+[^\>]*>)", "&#39;\\1", $ret);
				$ret = eregi_replace("&#8216; (<[a-zA-Z]+[^\>]*>)", "&#8216;\\1", $ret);
				$ret = eregi_replace("&lsquo; (<[a-zA-Z]+[^\>]*>)", "&lsquo;\\1", $ret);
				$ret = eregi_replace("&#8220; (<[a-zA-Z]+[^\>]*>)", "&#8220;\\1", $ret);
				$ret = eregi_replace("&ldquo; (<[a-zA-Z]+[^\>]*>)", "&ldquo;\\1", $ret);
				$ret = eregi_replace("(<[a-zA-Z]+[^\>]*>) (<[a-zA-Z]+[^\>]*>)", "\\1\\2", $ret);
			}
			return ($ret);
		}
		
		function codeQuotes($text)
		{
			$text = ereg_replace('&#39;' , '"', $text);
			$text = eregi_replace('&quot;' , "'", $text);
			return $text;
		}
		
		function print_item_set(&$itemSet, $listInfo, $relative='relative') 
		{
			global $bdprss_db, $bdprssTagSet, $bdprssCacheItem, $bdprssList;
			
			if(!$itemSet) return;
			
			// global variables
			$lmaxlength = $listInfo->{$bdprss_db->lmaxlength};
			$llaunchnew = $listInfo->{$bdprss_db->llaunchnew};
			$maxwordlength = $listInfo->{$bdprss_db->lmaxwordlength};
			$keepTagSet = $listInfo->{$bdprss_db->lallowablexhtmltags};
			
			// map the acceptable tags
			$tagSet = '';
			if($keepTagSet)
			{
				$kts = preg_split("','", $keepTagSet, -1, PREG_SPLIT_NO_EMPTY);
				foreach($kts as $t)
				{
					if(!$t) continue;
					$u = $bdprssTagSet[$t];
					foreach($u as $v)
						$tagSet .= "$v,";
				}
			}
			
			// decode any quotes in the formatting
			
			$lpreitemset= BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpreitemset});
			$lpostitemset = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostitemset});
			$lpreitem = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpreitem});
			$lpostitem = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostitem});
			$lpreitemname = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpreitemname});
			$lpostitemname = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostitemname});
			$lbetwixtsiteitem = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lbetwixtsiteitem});
			$lpreitemage = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpreitemage});
			$lpostitemage = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostitemage});
			$lpreitembody = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpreitembody});
			$lpostitembody = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostitembody});
			
			$liscachable = ($listInfo->{$bdprss_db->liscachable} == 'Y');
			$lcacheviewpage = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lcacheviewpage});
			$lidentifier = $listInfo->{$bdprss_db->lidentifier};
			
			echo $lpreitemset; 
			
			foreach($itemSet as $r) 
			{
				// work the description
				$inum = $r->{$bdprss_db->iidentifier};
				$desc = $r->{$bdprss_db->iitemtext};
				$desc = BDPRSSOUTPUT::packageItemText($desc, $lmaxlength, $maxwordlength, true, $tagSet);
				
				//work the item name
				$item =  BDPRSSOUTPUT::packageItemText($r->{$bdprss_db->iitemname});
				
				echo "$lpreitem";
				
				echo $lpreitemname;
				
				if($listInfo->{$bdprss_db->loutputsitename} == 'Y') 
				{
					$site = $bdprss_db->get_site_by_id($r->{$bdprss_db->ifeedidentifier});
					echo "<a href='".$site->{$bdprss_db->csiteurl}."' ";
					if($llaunchnew == 'Y') echo "target='_blank' ";
					echo "title='".$site->{$bdprss_db->cdescription}."'>".
						$site->{$bdprss_db->csitename}.
						"</a>";
					echo $lbetwixtsiteitem;
				}
				
				if(BDPRSS2_DEBUG) 
					echo '<!-- ' . date('r', $r->{$bdprss_db->iitemtime} ) . ' -->';
				
				$link = $r->{$bdprss_db->iitemurl};
				echo"<a href='$link'";
				if($llaunchnew == 'Y') echo " target='_blank'";
				echo ">$item</a>";
				
				echo $lpostitemname;
				
				if($listInfo->{$bdprss_db->lage} == 'Y') 
				{
					if($r->{$bdprss_db->iitemtime}>100000) 
					{
						echo $lpreitemage;
						if($relative=='relative')
							echo "Posted " . BDPRSS2::getage($r->{$bdprss_db->iitemtime});
						else
							echo date('l j F G:i:s T Y', $r->{$bdprss_db->iitemtime});
						echo $lpostitemage;
					}
				}					
						
				if($listInfo->{$bdprss_db->lshort} != 'Y') 
				{
					echo $lpreitembody;
					echo "$desc ";
					
					if($liscachable && $lcacheviewpage)
					{
						if(!ereg('\?', $lcacheviewpage))
							$joiner = '?';
						else
							$joiner = '&amp;';
						
						echo " [<a href='$lcacheviewpage".
							"$joiner$bdprssCacheItem=$inum&amp;$bdprssList=$lidentifier'";
						if($llaunchnew == 'Y') echo " target='_blank'";
						echo " rel='nofollow'>Cache</a>] ";
					}
					
					echo "[<a href='$link' ";
					if($llaunchnew == 'Y') echo "target='_blank' ";
					echo ">Link</a>]";
					
					echo $lpostitembody;
				}
				
				echo $lpostitem."\n";
			}
			echo $lpostitemset."\n"; 
		}

		function putsiteheader($prehead, $posthead, $siteaddress, $description, $sitename)
		{
			echo "$prehead<a href='$siteaddress' title='$description'>$sitename</a>$posthead\n";
		}
		
		function output($list_id) 
		{
		/* output() --	prints the RSS feeds using output list format $list_id
		 *	    --	returns TRUE if successful, and false if unsuccessful
		 *	    --	THIS IS THE MAIN USER FUNCTION!!!
		 */
			global $bdprss_db;
			
			$listInfo = $bdprss_db->get_list($list_id);
			if(!$listInfo) {
				echo "<!-- BDPRSSOUTPUT::output($list_id) could not find list #$list_id -->\n";
				return FALSE;
			}
			
			$ltype = $listInfo->{$bdprss_db->ltype};
			$llistall = ($listInfo->{$bdprss_db->llistall} == 'Y');
			$litemspersite = $listInfo->{$bdprss_db->litemspersite};
			$lurls = $listInfo->{$bdprss_db->lurls};
			$ids = preg_split("','", $lurls, -1, PREG_SPLIT_NO_EMPTY);
			
			$loutputsitename = $listInfo->{$bdprss_db->loutputsitename};
			
			$arg = $_SERVER['QUERY_STRING'];
			$regs = array();
			if(ereg("$bdprssdate=([0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9])", $arg, $regs))
			{
				$itemdate = $regs[1]; 
				$ltype = 'daterecentitem';
			}
			elseif(ereg("$bdprssmonth=([0-9][0-9][0-9][0-9]-[0-1][0-9])", $arg, $regs))
			{
				$itemdate = $regs[1]; 
				$ltype = 'monthrecentitem';
			}
			
			switch($ltype)
			{
			case 'countrecentitem':
				if($llistall)
					$itemSet = $bdprss_db->getItems(false, $litemspersite);
				else 
					$itemSet = $bdprss_db->getItems(false, $litemspersite, $ids);
				
				BDPRSSOUTPUT::print_item_set($itemSet, $listInfo, 'relative');
				
				break;
			
			case 'daterecentitem':
			case 'monthrecentitem':
				if(!isset($itemdate))
				{
					$relative = 'relative';
					$itemdate = date('Y-m-d');
				}
				else
					$relative = 'absolute';
				
				if($llistall)
					$itemSet = $bdprss_db->getItems(false, 0, FALSE, $itemdate);
				else 
					$itemSet = $bdprss_db->getItems(false, 0, $ids, $itemdate);
				
				BDPRSSOUTPUT::print_item_set($itemSet, $listInfo, $relative);
				
				break;
				
			case 'sitealpha':
			case 'siteupdate':
				$result = $bdprss_db->get_all_sites($ltype);
				
				if($result) 
				{
					$lprefeed = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprefeed});
					$lpostfeed = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostfeed});
					$lpresite = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpresite});
					$lpostsite = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostsite});
					$lpresitename = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpresitename});
					$lpostsitename = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostsitename});
					
					// output
					echo $lprefeed;
					
					foreach($result as $r) 
					{
						$siteurl = $r->{$bdprss_db->csiteurl};
						$feedurl = $r->{$bdprss_db->cfeedurl};
						$desc = $r->{$bdprss_db->cdescription};
						$name = $r->{$bdprss_db->csitename};
						$feedID = $r->{$bdprss_db->cidentifier};
						
						if(!$llistall && array_search($r->{$bdprss_db->cidentifier}, $ids) === FALSE) 
							continue; 
						
						echo $lpresite;
						
						if($loutputsitename === 'Y') 
							BDPRSSOUTPUT::putsiteheader($lpresitename, $lpostsitename, $siteurl, $desc, $name);
						
						$itemSet = $bdprss_db->getItems($feedID, $litemspersite);
						
						$listInfo->{$bdprss_db->loutputsitename} = FALSE;
						BDPRSSOUTPUT::print_item_set($itemSet, $listInfo);
						
						echo $lpostsite."\n";
					}
					echo $lpostfeed."\n";
				}
			}
			return TRUE;
		}
		
		
		/* ----- Archibe Functions ----- */
		
		function archiveList($listID, $mode, $period, $format)
		{
			// Args:
			//	$listID -- the list identifier
			//	$mode -- the argchive period is a day or month
			//	$period -- the number of archive periods
			//	$format -- the PHP date format code
			
			global $bdprss_db, $bdprssdate, $bdprssmonth;
			
			$listInfo = $bdprss_db->get_list($listID);
			if(!$listInfo) {
				echo "<!-- BDPRSS2::archiveList($listID) could not find list #$listID -->\n";
				return FALSE;
			}
			
			$period = intval($period);
			if($period == 0)
				$backthen = 1000000;
			else
			{
				switch($mode)
				{
						case 'month':
							$backthen = time() - ($period * 31 * 7 * 24 * 60 * 60);
							break;
						case 'day':
						default:
							$backthen = time() - ($period * 24 * 60 * 60);
							$mode = 'day';
				}
			}
			
			$llistall = ($listInfo->{$bdprss_db->llistall} == 'Y');
			$lurls = $listInfo->{$bdprss_db->lurls};
			$ids = preg_split("','", $lurls, -1, PREG_SPLIT_NO_EMPTY);

			if($listall)
				$listSet = $bdprss_db->getArchiveList($backthen, $mode);
			else 
				$listSet = $bdprss_db->getArchiveList($backthen, $mode, $ids);
			
			if($listSet)
			{
				$lprearchivelist = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprearchivelist});
				$lpostarchivelist = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostarchivelist});
				$lprearchivedate = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprearchivedate});
				$lpostarchivedate = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostarchivedate});
				
//				$larchivedateformat = $listInfo->{$bdprss_db->larchivedateformat};
				
				$larchiveviewpage = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->larchiveviewpage});

				if($larchiveviewpage)
					$self = $larchiveviewpage;
				else
					$self = get_permalink(get_the_ID());
					
				$self = ereg_replace("&$bdprssdate=[0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]", '', $self);
				$self = ereg_replace("\?$bdprssdate=[0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]", '', $self);
				$self = ereg_replace("&$bdprssmonth=[0-9][0-9][0-9][0-9]-[0-1][0-9]", '', $self);
				$self = ereg_replace("\?$bdprssmonth=[0-9][0-9][0-9][0-9]-[0-1][0-9]", '', $self);
				
				if(!ereg('\?', $self))
					$joiner = '?';
				else
					$joiner = '&amp;';
				
				echo $lprearchivelist;
				foreach($listSet as $l)
				{
					$date = $l->{$bdprss_db->iitemdate};
					list( $year, $month, $day) = split('-', $date);
					$formatedDate = date($format, mktime(12, 0, 0, $month, $day, $year));
					$formatedDate = ereg_replace(' ', '&nbsp;', $formatedDate);
					echo $lprearchivedate;
					if($mode=='day')
						echo "<a href='$self$joiner$bdprssdate=$date'>$formatedDate</a>";
					else
					{
						$date = $year ."-". $month;
						echo "<a href='$self$joiner$bdprssmonth=$date'>$formatedDate</a>";
					}
					echo $lpostarchivedate;
				}
				echo $lpostarchivelist;
			}
		}

		function archiveDate($list_id, $dateFormat)
		{
			global $bdprss_db, $bdprssdate, $bdprssmonth;
			
			$listInfo = $bdprss_db->get_list($list_id);
			if(!$listInfo) {
				echo "<!-- BDPRSS2::archiveDate($list_id) could not find list #$list_id -->\n";
				return FALSE;
			}
			
			$arg = $_SERVER['QUERY_STRING'];
			$regs = array();
			if(ereg("$bdprssdate=([0-9][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9])", $arg, $regs))
			{
				$itemdate = $regs[1]; 
			}
			if(!$itemdate)
				$itemdate = date('Y-m-d');
				
			list($year, $month, $day) = split('-', $itemdate);
			
			echo date($dateFormat, mktime(12, 0, 0, $month, $day, $year));
		}
		
		
		/* ----- Cache functions ----- */
		
		function viewCache()
		{
			global $bdprss_db, $bdprssList, $bdprssCacheItem;
			
			$listnum = $_GET[$bdprssList];
			$cacheItem = $_GET[$bdprssCacheItem];
			
			$listInfo = $bdprss_db->get_list($listnum);
			if(!$listInfo) 
			{
				echo "<!-- BDPRSSOUTPUT::viewCache() could not find list number $listnum -->\n";
				echo "<p>The cached item you sought could not be found</p>\n";
				return FALSE;
			}
			
			$item = $bdprss_db->getItemByID($cacheItem);
			if(!$item) 
			{
				echo "<!-- BDPRSSOUTPUT::viewCache() could not find item number $cacheItem -->\n";
				echo "<p>The cached item you sought could not be found</p>\n";
				return FALSE;
			}
			
			$site = $bdprss_db->get_site_by_id($item->{$bdprss_db->ifeedidentifier});
			if(!$site)
			{
				echo "<!-- BDPRSSOUTPUT::viewCache() could not find site ".
					$item->{$bdprss_db->ifeedurl}. " -->\n";
				echo "<p>The cached item you sought could not be found</p>\n";
				return FALSE;
			}
			
			$lprecacheitem = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprecacheitem});
			$lpostcacheitem = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostcacheitem});
			$lprecachetitle = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprecachetitle});
			$lpostcachetitle = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostcachetitle});
			$lprecachesite = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprecachesite});
			$lpostcachesite = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostcachesite});
			$lprecacheitemtime = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprecacheitemtime});
			$lpostcacheitemtime = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostcacheitemtime});
			$lprecacheupdatetime = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lprecacheupdatetime});
			$lpostcacheupdatetime = BDPRSSOUTPUT::codeQuotes($listInfo->{$bdprss_db->lpostcacheupdatetime});
			
			$llaunchnew = $listInfo->{$bdprss_db->llaunchnew};
			
			$desc = BDPRSSOUTPUT::codeQuotes($item->{$bdprss_db->iitemtext});
			$desc = eregi_replace('&lt;' , '<', $desc);
			$desc = eregi_replace('&gt;' , '>', $desc);
			
			$title =  BDPRSSOUTPUT::packageItemText($item->{$bdprss_db->iitemname});
			$link = $item->{$bdprss_db->iitemurl};
			
			$sitename = $site->{$bdprss_db->csitename};
			$siteurl = $site->{$bdprss_db->csiteurl};
			$sitedesc = $site->{$bdprss_db->cdescription};
			
			echo $lprecacheitem;
			
			echo $lprecachetitle;
			if($link)
			{
				echo"<a href='$link'";
				if($llaunchnew == 'Y') echo " target='_blank'";
				echo '>';
			}
			echo $title;
			if($link) echo '</a>';
			echo $lpostcachetitle;
			
			echo $desc;
			
			echo $lprecachesite;
			if($siteurl)
			{
				echo"<a href='$siteurl' title='$sitedesc'";
				if($llaunchnew == 'Y') echo " target='_blank'";
				echo '>';
			}
			echo $sitename;
			if($siteurl) echo '</a>';
			echo $lpostcachesite;
			
			$format = 'D j M G:i T Y';
			
			echo $lprecacheitemtime;
			echo date($format, $item->{$bdprss_db->iitemtime});
			echo $lpostcacheitemtime;
			
			echo $lprecacheupdatetime;
			echo date($format, $item->{$bdprss_db->iitemupdate});
			echo $lpostcacheupdatetime;
			
			echo $lpostcacheitem;
		}
		
		
		/* ----- RSS Functions ----- */
		
		function strip($text)
		{
			$text = ereg_replace('&lt;',	 	'<',  	$text);
			$text = ereg_replace('&gt;',	 	'>',  	$text);
			return strip_tags($text);
		}
		
		function getTagList()
		{
			global $bdprssTagSet;			

			$tagSet ='';
			$keepTagSet = array('Links', 'Images', 'Paragraphs', 'Line breaks', 'Italics',  
				'Underlining', 'Bolding', 'Other text formating', 'Tables', 'Lists', 
				'Headings', 'Block quotes', 'Separators');
			foreach($keepTagSet as $t)
			{
				$u = $bdprssTagSet[$t];
				foreach($u as $v)
					$tagSet .= "$v,";
			}
			return($tagSet);
		}
		
		function putHeader()
		{
			$charset = get_option('blog_charset');
			header("Content-type:text/xml; charset=$charset", true);
			echo "<?xml version='1.0' encoding='$charset'?>\n";
			echo '<rss version="2.0">'."\n\n";
		}
		
		function rssServe(&$listInfo)
		{
			global $bdprss_db;			
			
			BDPRSSOUTPUT::putHeader();
?>
	<channel>
	<title><?php echo BDPRSSOUTPUT::strip($listInfo->{$bdprss_db->lname}); ?></title>
	<link><?php echo $listInfo->{$bdprss_db->lrsslink}; ?></link>
	<description><![CDATA[ <?php echo $listInfo->{$bdprss_db->lrssdescription}; ?> ]]></description>
	<generator><?php echo BDPRSS2_PRODUCT . ' version ' . BDPRSS2_VERSION; ?></generator>

<?php
			$count = intval($listInfo->{$bdprss_db->lrssnumbersingle});
			if($listInfo->{$bdprss_db->llistall} == 'Y')
				$itemSet = $bdprss_db->getItems(false, $count);
			else
			{
				$ids = preg_split("','", $listInfo->{$bdprss_db->lurls}, -1, PREG_SPLIT_NO_EMPTY);
				$itemSet = $bdprss_db->getItems(false, $count, $ids);
			}
			
			$dateFormat = 'D, d M Y H:i:s +0000';
			$tagSet = '';
			if($r->{$bdprss_db->lrssfullorsummary} != 'FULL')
				$tagSet = BDPRSSOUTPUT::getTagList();
			
			foreach($itemSet as $r)
			{
				if(!$r) continue;
				
				$title = '';
				$s = $bdprss_db->get_site_by_id(intval($r->{$bdprss_db->ifeedidentifier}));
				if($s) $title = $s->{$bdprss_db->csitename} . " » ";
				$title .= $r->{$bdprss_db->iitemname};
				$title = BDPRSSOUTPUT::strip($title);

				$desc = $r->{$bdprss_db->iitemtext};
				if($listInfo->{$bdprss_db->lrssfullorsummary} != 'FULL')
					$desc = BDPRSSOUTPUT::packageItemText($desc, 60, 0, TRUE, $tagSet);
				$desc = ereg_replace('&lt;',	'<',  	$desc);
				$desc = ereg_replace('&gt;',	'>',  	$desc);
				$desc = ereg_replace('&quot;',	'"', 	$desc);
				$desc = ereg_replace('&#39;', 	"'",  	$desc);
				
				$pubDate = gmdate($dateFormat, intval($r->{$bdprss_db->iitemtime}));
?>
	<item>
		<title><?php echo $title; ?></title>
		<link><?php echo $r->{$bdprss_db->iitemurl}; ?></link>
		<guid><?php echo $r->{$bdprss_db->iitemurl}; ?></guid>
		<description><![CDATA[ <?php echo $desc; ?> ]]></description>
		<pubDate><?php echo $pubDate; ?></pubDate>
	</item>

<?php
			}
?>
	</channel>
</rss>

<?php
		}
		
	}
}
