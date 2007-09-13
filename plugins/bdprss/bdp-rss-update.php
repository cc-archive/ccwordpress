<?php

if( !class_exists('BDPRSSUPDATE') ) 
{
	class BDPRSSUPDATE
	{
		function update(&$row) 
		{
		/* update(&$row) 
		 *  -- does the grunt work of updating a feed, 
		 *  -- specified by a row from the site-table
		 */
			global $bdprss_db;
			
			// Check we have a row from the site-table
			if(!isset($row) || !$row || !$row->{$bdprss_db->cidentifier} || !$row->{$bdprss_db->cfeedurl})
			{
				$bdprss_db->recordError('Snark', 
					"Snark: update() called without a row from the siteTable (this should never happen)");
				return;
			}
			$now = time();
			$feedID = $row->{$bdprss_db->cidentifier};
			$url = $row->{$bdprss_db->cfeedurl};
			$lastupdated = (int) $row->{$bdprss_db->cupdatetime};
			
			// set the next poll time
			$siteArray = array();
			$siteArray['cidentifier'] = $feedID;
			$siteArray['clastpolltime'] = $now;
			if($row->{$bdprss_db->cpollingfreqmins})
				$siteArray['cnextpolltime'] = $now + (60 * (int) $row->{$bdprss_db->cpollingfreqmins});
			else
				$siteArray['cnextpolltime'] = $now + (60 * (int) get_option('bdprss_update_frequency'));
			$bdprss_db->updateTable($bdprss_db->sitetable, $siteArray, 'cidentifier');
			
			// Clear errorBase
			$bdprss_db->deleteErrors($url);
			
			// Get the feed
			$feed = new BDPRSSFeed($url);
			$pfeed = $feed->parse();
			if(!$pfeed)
			{
				$bdprss_db->recordError($url, "Failed to parse $url");
				return;
			}
			
			// extract and save key site information
			$siteArray['csitename'] = mb_substr($pfeed['title'], 0, 250);
			$siteArray['csiteurl'] =  mb_substr($pfeed['link'], 0, 250);
			$siteArray['cdescription'] = mb_substr(($pfeed['description'] ? 
				$pfeed['description'] : $pfeed['tagline']), 0, 250);
			$siteArray['cupdatetime'] = $now;
			$bdprss_db->updateTable($bdprss_db->sitetable, $siteArray, 'cidentifier',
				$row->{$bdprss_db->csitenameoverride}=='Y');
				
			// Establish virginity - an important concept for sites that don't provide item timestamps
			$virgin = ($lastupdated == 1);
			if(BDPRSS2_DEBUG) $bdprss_db->recordError($url, "I am a virgin site");
			
			// extract and save key item information
			$counter = 1;
			foreach ($pfeed['items'] as $item) 
			{
				$ticks = 0;
				
				$link = $item['link'];
				$title = $item['title'];
				
				if(!$link && isset($item['guid']))
				{
					// A work around for Penny Sharpe's blog - http://pennysharpe.com/redleather/
					$link = $item['guid']; // in RSS guid stands for globally unique identifier 
				}
					
				// some error reporting sequences
				if($link) 
					{ $preError = "<a href='$link'>"; $postError = '</a>'; } 
				if($title && $link)
					$errorLink = ' ('.$preError.$title.$postError.') ';
				elseif($title && !$link)
					$errorLink = ' ('.$title.') ';
				elseif($link && !$title)
					$errorLink = ' ['.$preError.'link'.$postError.'] ';
				else
					$errorLink = '';
					
				if(!$title)
				{
					$bdprss_db->recordError($url, "No title in feed for item $errorLink");
					// lets see if we can make a half a meaningful title from the link
					$title = 'No title';
				}
				
				if(!$link) 
				{
					$bdprss_db->recordError($url, "No link in feed for item $errorLink");
					continue; // a URL link for the item is needed for the database
				}
				
				$link =	mb_substr($link, 0, 250);	// keep it short buddy 
				$title = mb_substr($title, 0, 250);	// keep it short buddy 
					
				// get the itemtext
				if($item['content:encoded']) 
					$itemtext = $item['content:encoded'];
				elseif($item['description'])
					$itemtext = $item['description'];
				elseif($item['content']) 
					$itemtext = $item['content'];
				elseif($item['summary']) 
					$itemtext = $item['summary'];
				else
					$itemtext = '';
					
				// get the time - this is tricky because many feeds don't provide a timestamp
				$ticks = 0;
				$timeType = 
					array('pubDate', 'dc:date', 'created', 'issued', 'published', 'updated', 'modified');
				$done = FALSE;
				foreach($timeType as $t)
				{
					if($item[$t])
					{
						$done = TRUE;
						$ticks = strtotime($item[$t]);
						if($ticks > 0) break;
						$ticks = preg_replace("'[- +a-z]*$'si", '', $item[$t]);
						$ticks = strtotime($ticks);
						if($ticks > 0) break;
						$ticks = BDPRSSUPDATE::w3cdtf($item[$t]); 
						if($ticks > 0) break;
						$bdprss_db->recordError($url, 'Exact time of item post not correctly encoded: '.
							$t.'['.$item[$t]."] $errorLink");
						$done = FALSE;
					}
					if($ticks < 0)	$ticks = 0;
				}
				if(!$done) $bdprss_db->recordError($url, 
					"No time-stamp in feed for post item $errorLink");
				$rawTicks = $ticks;
				$gmtadjust_seconds = intval(floatval($row->{$bdprss_db->cgmtadjust}) * 3600);
				$ticks += $gmtadjust_seconds;
				
				// make time adjustments -- including for those feeds without timestamps
				$windforward = FALSE;
				$windback = FALSE;
				$gmt_adjust = 0.0;
				if($ticks <= 1000000) 
				{
					if($virgin) 
						$ticks = 0; 
					else 
						$ticks = $now - $counter;
				} 
				else
				{
					if (!$virgin) 
					{
						// reprogram any necessary GMT adjustments for out of sync time stamps
						// we can change $ticks here as ...
						// ... $bdprss_db->update_itemstable() only updates $ticks on inserts
						// ... it will not affect updates
						if($ticks > intval($now+300)) 
						{
							$windback = TRUE;	
							$gmt_adjust = -0.5;
						}
						if($ticks < intval($lastupdated-300)) {
							$windforward = TRUE;
							$ticks = $now - $counter; 
							$gmt_adjust = 0.5;
						}
					}
				}
				
				// one final tweak to prevent forward time
				if($ticks > $now) $ticks = $now - $counter;
				
				// update/insert item information
				if ($bdprss_db->updateItem($feedID, $title, $counter, $itemtext, $link, $ticks) && !$virgin) 
				{
					// it was an item insert (and not an update) for an old feedurl
					// -- let's see if we need to do an automatic adjustmemt to the time!
					if($windback || $windforward) 
					{
						$bdprss_db->recordError($url, "Raw time stamp: " . 
							BDPRSS2::getage($rawTicks). $errorLink);
						$gmt_adjust += floatval($row->{$bdprss_db->cgmtadjust});
						if($gmt_adjust > 48.0) $gmt_adjust = 48.0;
						if($gmt_adjust < -48.0) $gmt_adjust = -48.0; 
						$siteArray['cgmtadjust'] = $gmt_adjust;
						$bdprss_db->updateTable($bdprss_db->sitetable, $siteArray, 'cidentifier',
							$row->{$bdprss_db->csitenameoverride}=='Y');
						$bdprss_db->recordError($url, "New GMT adjustment: $gmt_adjust hours $errorLink");
					}
				}
				$counter++;
			}
			$bdprss_db->delete_old_items($url);
		}
		
		function w3cdtf($dateString='') 
		{
			/* w3cdtf() -- modified from parse_w3cdtf() in functions-rss.php in Wordpress!
			 */
			 
			// regex to match wc3dtf
			$pat = "/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})(:(\d{2}))?(?:([-+])(\d{2}):?(\d{2})|(Z))?/i";
			
			if ( preg_match( $pat, $dateString, $match ) ) 
			{
				list( $year, $month, $day, $hours, $minutes, $seconds) = 
					array( intval($match[1]), intval($match[2]), intval($match[3]), 
					intval($match[4]), intval($match[5]), intval($match[6]));
				
				if ( $match[10] != 'Z' ) 
				{
					list( $tz_mod, $tz_hour, $tz_min ) =
						array( $match[8], intval($match[9]), intval($match[10]));
					
					// zero out the variables
					if ( ! $tz_hour ) { $tz_hour = 0; }
					if ( ! $tz_min ) { $tz_min = 0; }
					
					$offset = (($tz_hour*60)+$tz_min)*60;
					
					// is timezone ahead of GMT?  then subtract offset
					if ( $tz_mod == '+' ) { $offset *= -1; }
				}
				else
				{
					$offset = 0;
				}
				
				$secondsSinceEpoch = gmmktime( $hours, $minutes, $seconds, $month, $day, $year) + $offset;
			}
			else
			{
				$secondsSinceEpoch = -1;	// error
			}
			
			return $secondsSinceEpoch;
		}
	}
}
