<?php

if( !class_exists('BDPTSSFeed') ) 
{

	class BDPRSSFeed 
	{
	/* This is a quick and dirty class to sort through a single channel feed.
	 * But that should not be a problem as RSS 2.0 is a single channel standard.
	 */
		var $url;
		
		function BDPRSSFeed($url) 
		{
			$this->url = $url;
		}
		
		function title_recode($title, $captureTags=false) 
		{
		/* get rid of difficult characters - we don't want quotes as they affect the SQL and echos
		 * we don't want XHTML code here as it might cause problems when printed
		 * encode unencoded ampersands
		 */
			// blogspot puts encoded tags into the feed stream - decode them
			$title = eregi_replace("\&lt;", 		'<', 		$title);
			$title = eregi_replace("\&gt;", 		'>', 		$title);
			$title = eregi_replace("\&amp;([a-z]+);",	'&\\1;',	$title);
			$title = eregi_replace("\&amp;#([0-9]+);",	'&#\\1;',	$title);
			$title = eregi_replace("\&amp;#x([a-e0-9]+);",'&#x\\1;',	$title);
			
			// remove CDATA tags - leave XHTML tags
			$title = preg_replace("'<!\[CDATA\[(.*?)\]\]>'si", '\\1', $title);
			
			// tidy-up quotes - prevents SQL insertion
			$title = ereg_replace('"',	 		'&quot;', 	$title);
			$title = ereg_replace("'", 			'&#39;',  	$title);
			$title = eregi_replace('\&apos;',	'&#39;',  	$title); // old browser fix
			
			// find unencoded ampersands and encode them!
			$title = ereg_replace('<',	 		'&lt;',  	$title);
			$title = ereg_replace('>',	 		'&gt;',  	$title);
			$title = eregi_replace('\&([a-z]+);', 	'<\\1>', 	$title); // alpha
			$title = eregi_replace('\&(#[0-9]+);',	'<\\1>', 	$title); // decimal
			$title = eregi_replace('\&(#x[a-e0-9]+);',	'<\\1>', 	$title); // hex
			$title = eregi_replace('\&', 		'&amp;', 	$title);
			$title = eregi_replace('<([^>]+)>',		'&\\1;',	$title);
			
			// tidy-up white spaces
			$title = eregi_replace('\&nbsp;', 	' ',	$title);
//			$title = eregi_replace('[\n\r\s]+', ' ',	$title);
			
			return $title;
		}
		
		function rebaseAddresses($itemtext, $siteURL)
		{
			// simplify and manipulate links in the itemtext
			// 1 - restore quotes and angle brackets -- just for a moment
			$itemtext = eregi_replace('&quot;',	 	'"', 	$itemtext);
			$itemtext = eregi_replace('&#39;', 		"'",  	$itemtext);
			$itemtext = eregi_replace('&lt;',	 	'<', 	$itemtext);
			$itemtext = eregi_replace('&gt;', 		'>',  	$itemtext);
			
			// 2 - simplify and standardise the HTML
			$itemtext = eregi_replace('<img ([^>]*)src="([^">]*)"([^>]*) />',
				"<img src='\\2' \\1 \\3 />", $itemtext);
			$itemtext = eregi_replace("<img ([^>]*)src='([^'>]*)'([^>]*) />",
				"<img src='\\2' \\1 \\3 />", $itemtext);
			$itemtext = eregi_replace("<img (src='[^'>]*')([^>]*)width=['\"]([^'\">]*)['\"]([^>]*) />",
				"<img \\1 width='\\3' \\2 \\4 />", $itemtext);
			$itemtext = eregi_replace(
				"<img (src='[^'>]*' width='[^'>]*')[^>]*height=['\"]([^'\">]*)['\"][^>]* />",
				"<img \\1 height='\\2' />", $itemtext);
			$itemtext = eregi_replace("<a [^>]*href='([^\'>]*)'[^>]*>",
				"<a href='\\1' target='_blank' rel='nofollow'>", $itemtext);
			$itemtext = eregi_replace('<a [^>]*href="([^"\'>]*)"[^>]*>',
				"<a href='\\1' target='_blank' rel='nofollow'>", $itemtext);

			// 3 - substitute in full address to relative addresses
			$itemtext = eregi_replace("<img src='/([^'\>]+'[^\>]+) />",
				"<img src='$siteURL/\\1 />", $itemtext);
			$itemtext = eregi_replace( "<a href='/([^'>]+'[^\>]+)>",
				"<a href='$siteURL/\\1>", $itemtext);
				
			// 4 -- other tidy-ups
			$itemtext = eregi_replace('<p [^>]*>', '<p>', $itemtext);
			$itemtext = eregi_replace('<li [^>]*>', '<li>', $itemtext);
			$itemtext = eregi_replace('<br[^>]* />', '<br />', $itemtext);

			//echo "<!-- DEBUG: $itemtext -->\n";

			// 5 - kill the quotes to be SQL secure
			$itemtext = eregi_replace('"',	 	'&quot;', 	$itemtext);
			$itemtext = eregi_replace("'", 		'&#39;',  	$itemtext);
			$itemtext = eregi_replace('<',	 	'&lt;', 	$itemtext);
			$itemtext = eregi_replace('>', 		'&gt;',  	$itemtext);
			
			return $itemtext;
		}

		function preg_capture($pattern, $subject) 
		{
			// start regular expression
			preg_match($pattern, $subject, $out);
			
			// if there is some result... process it and return it
			if(isset($out[1])) 
				return $out[1];
			else 
				// if there is NO result, return empty string
				return '';
		}
		
		function parse()
		{
			global $bdprss_db;
			
			$result = array();
			$result['items'] = array();
			
			$snoopy = new Snoopy();
			$snoopy->agent = BDPRSS2_PRODUCT . ' ' . BDPRSS2_VERSION;
			$snoopy->read_timeout = 8;	// THINK ABOUT THIS!
			$snoopy->curl_path = FALSE;	// THINK ABOUT THIS!
			$snoopy->maxredirs = 2;
			
			if(! @$snoopy->fetch($this->url))
			{
				$bdprss_db->recordError($this->url, "Could not open ".$this->url);
				return FALSE;
			}
			$content = $snoopy->results;
			
			if($snoopy->error) $bdprss_db->recordError($this->url, $snoopy->error);
			
			// guess feed type - favour rss feeds
			$tmp = $this->preg_capture("'<rss[^>]*?>(.*?)</rss>'si", $content, $matches);
			if($tmp) 
			{
				$content = $tmp;
				$feedtype = 'RSS';
			} 
			else 
			{
				$tmp = $this->preg_capture("'<rdf:RDF[^>]*?>(.*?)</rdf:RDF>'si", $content, $matches);
				if($tmp) 
				{
					$content = $tmp;
					$feedtype = 'RDF';
				} 
				else 
				{
					$tmp =  $this->preg_capture("'<feed[^>]*?>(.*?)</feed>'si", $content, $matches);
					if($tmp)
					{
						$feedtype = 'ATOM';
					}
					else 
					{
						$bdprss_db->recordError($this->url, "Feed is malformed and cannot be parsed");
						return FALSE;
					}
				}
			}
			$result['feedtype'] = $feedtype;
			
			//detect_order('WINDOWS-1252, UTF-8, ISO-8859-1');
			//$old_charset = get_option( 'blog_charset' ); //detect_encoding( $content ); 
			//$new_charset = get_option( 'blog_charset' ); 
			//$content = @convert_encoding($content, /*to*/$new_charset, /*from*/$old_charset);
			
			// split up channels - we are only going to parse the first channel!
			if($feedtype == 'RDF') 
			{
				$feed = $content;
				$channeltags = array ('title', 'link', 'description', 'dc:creator', 'dc:date');
				$itemtags = array('title', 'link', 'description', 'dc:date', 'dc:subject', 'dc:creator', );
				$item = 'item';
			} 
			elseif ($feedtype == 'RSS') 
			{
				$feed = $this->preg_capture("'<channel[^>]*?>(.*?)</channel>'si", $content);
				$channeltags = array ('title', 'link', 'description');
				$itemtags = 
					array('title', 'link', 'description', 'content:encoded', 'pubDate', 'dc:date', 'guid');
				$item = 'item';
			} 
			elseif ($feedtype == 'ATOM') 
			{
				$feed = $this->preg_capture("'<feed[^\>]*?>(.*?)</feed>'si", $content);
				$channeltags = array('title', 'tagline', 'link');
				$itemtags = array('title', 'summary', 'link', 'content', 
					'issued', 'modified', 'created', 'published', 'updated'); 
				$bloggerlink1 =
					"#<link[^\>]*href=[\"']([^\"']*)[\"'][^\>]*?type=[\"']text/html[\"'][^\>]*?>#si";
				$bloggerlink2 = 
					"#<link[^\>]*type=[\"']text/html[\"'][^\>]*?href=[\"']([^\"']*)[\"'][^\>]*?>#si";
				$bloggerlink3 = "#<link[^\>]*href=[\"']([^\"']*)[\"'][^\>]*>#si";
				$item = 'entry';
			} 
			
			if(BDPRSS2_DEBUG) $bdprss_db->recordError($this->url, "DEBUG feedtype: $feedtype");
			if(BDPRSS2_DEBUG) $bdprss_db->recordError($this->url, "DEBUG feedsize: ".strlen($feed));
			
			// get the feed information
			foreach($channeltags as $tag) 
			{
				if($feedtype == 'ATOM' && $tag == 'link') 
				{
					$tmp = $this->preg_capture($bloggerlink1, $feed);
					if(!$tmp) $tmp = $this->preg_capture($bloggerlink2, $feed);
					if(!$tmp) $tmp = $this->preg_capture($bloggerlink3, $feed);
				}
				else
					$tmp = $this->preg_capture("'<$tag.*?>(.*?)</$tag>'si", $feed);
				
				if(!$tmp) continue;
			
				$result[$tag] = $this->title_recode($tmp);
			}
			
			// manipulate site URL for use with indirect references
			$siteURL = $result['link'];
			if(!$siteURL)
				$bdprss_db->recordError($this->url, "Feed does not include a site URL");
			else
				$siteURL = eregi_replace("(http://[^/]*).*$", "\\1", $siteURL);
			
			// get the item information			
			$n = preg_match_all("'<$item(| .*?)>.*?</$item>'si", $feed, $items);
			if(!$n) 
			{
				$bdprss_db->recordError($this->url, "Feed did not contain any items");
				return $result;
			}
			
			$i = 0;
			foreach( $items[0] as $item ) 
			{
				foreach( $itemtags as $itag ) 
				{
					if($feedtype == 'ATOM' && $itag == 'link') 
					{
						$tmp = $this->preg_capture($bloggerlink1, $item);
						if(!$tmp) $tmp = $this->preg_capture($bloggerlink2, $item);
						if(!$tmp) $tmp = $this->preg_capture($bloggerlink3, $item);
					}
					else
						$tmp = $this->preg_capture("'<$itag.*?>(.*?)</$itag>'si", $item);
					
					if ($tmp == '' || !$siteURL) continue;
					
					$tmp = $this->title_recode($tmp);
					
					if($itag == 'content:encoded' || $itag == 'description' || 
					$itag == 'content' || $itag == 'summary')
						$tmp = $this->rebaseAddresses($tmp, $siteURL);
						
					$result['items'][$i][$itag] = $tmp;
				}
				$i++;
			}
			return $result;
		}
	}
}
?>
