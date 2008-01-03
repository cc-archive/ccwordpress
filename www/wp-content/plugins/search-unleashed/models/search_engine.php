<?php

class Search_Engine
{
	var $engine;
	var $search;
	var $referrer;
	
	function Search_Engine ($referrer)
	{
		if (isset ($referrer) && $referrer)
		{
			$this->referrer = $referrer;
			$this->this->search = '';
			
			$referrer = preg_replace ('@(http|https)://@', '', stripslashes (urldecode ($referrer)));
			$args     = explode ('?', $referrer);
			$query    = array ();

			if (count ($args) > 0)
				parse_str ($args[1], $query);

			if (substr ($referrer, 0, strlen ($_SERVER['SERVER_NAME'])) == $_SERVER['SERVER_NAME'] && (isset ($query['s']) || strpos ($referrer, '/search/') !== false))
			{
				$this->engine = 'local';
				
				if (isset ($query['s']))
					$this->search = $query['s'];
				else
					$this->search = str_replace ('/search/', '', str_replace ($_SERVER['SERVER_NAME'], '', $referrer));
			}
			else if (strpos ($referrer, 'google') !== false)
			{
				$this->engine =  'google';
				$this->search = $query['q'];
				$this->search = preg_replace ('/\w+\:(.*)/', '$1', $this->search);
			}
			else if (strpos ($referrer, 'yahoo') !== false)
			{
				$this->engine =  'yahoo';
				$this->search = $query['p'];
			}
			else if (strpos ($referrer, 'sogou') !== false)
			{
				$this->engine =  'sogou';
				$this->search = $query['query'];
			}
			else if (strpos ($referrer, 'baidu') !== false)
			{
				$this->engine =  'baidu';
				$this->search = $query['wd'];
			}
			else if (strpos ($referrer, 'lycos') !== false)
			{
				$this->engine =  'lycos';
				$this->search = $query['query'];
			}
			else if (strpos ($referrer, 'altavista') !== false)
			{
				$this->engine =  'altavista';
				$this->search = $query['q'];
			}
			else if (strpos ($referrer, 'search.msn') !== false || strpos ($referrer, 'search.live') !== false)
			{
				$this->engine =  'msn';
				$this->search = $query['q'];
			}
			else if (strpos ($referrer, 'yandex') !== false)
			{
				$this->engine =  'yandex';
				$this->search = $query['text'];
				
				// Yandex arrives in CP1251 format, so we need to convert to whatever format the blog is in
				if (function_exists ('mb_convert_encoding'))
					$this->search = mb_convert_encoding ($this->search, get_bloginfo ('blog_charset'), 'cp1251');
				else if (function_exists ('iconv'))
					$this->search = iconv ('cp1251', get_bloginfo ('blog_charset'), $this->search);
			}
		}
	}

	function get_engine ()
	{
		return $this->engine;
	}
	
	function get_search ()
	{
		return $this->search;
	}
	
	function get_engine_name ()
	{
		$names = array
		(
			'msn'       => 'MSN',
			'altavista' => 'Altavista',
			'lycos'     => 'Lycos',
			'baidu'     => 'Baidu',
			'sogou'     => 'Sogou',
			'google'    => 'Google',
			'yandex'    => 'Yandex',
			'local'     => 'Local'
		);
		
		return $names[$this->engine];
	}
	
	function is_incoming_search ()
	{
		return ($this->engine && $this->search) ? true : false;
	}
}

?>