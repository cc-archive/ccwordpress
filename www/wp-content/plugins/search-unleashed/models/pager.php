<?php
/**
 * This program and all files not otherwise specifically mentioned are copyright of the
 * author and may be used in any commercial or non-commercial environment.  It may not
 * be sold or derived from, and the author reserves the right to change the license
 * at any time (including Christmas)
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @author     John Godley
 * @version    0.2.1
 * @copyright  Copyright &copy; 2007 John Godley, All Rights Reserved
 */

class Search_Pager
{
	var $url             = null;
	var $current_page    = 1;
	var $per_page        = 25;
	var $total           = 0;
	var $order_by        = null;
	var $order_original  = null;
	var $order_direction = null;
	var $steps           = array ();
	var $search          = null;
	
	function Search_Pager ($data, $url, $orderby = '', $direction = 'DESC')
	{
		// Remove all pager params from the url
		$this->url = $url;
		
		if (isset ($data['curpage']))
			$this->current_page = intval ($data['curpage']);
			
		if (isset ($data['perpage']))
			$this->per_page = intval ($data['perpage']);

		if ($orderby != '')
			$this->order_by = $orderby;
		
		if (isset ($data['orderby']))
			$this->order_by = $data['orderby'];
			
		$this->order_direction = $direction;
		$this->order_original  = $orderby;
		if (isset ($data['order']))
			$this->order_direction = $data['order'];
		
		$this->search = $data['search'];
		$this->steps = array (10, 25, 50, 100, 250);
		$this->url = str_replace ('&', '&amp;', $this->url);
		$this->url = str_replace ('&&amp;', '&amp;', $this->url);
	}
	
	function set_total ($total)
	{
		$this->total = $total;

		if ($this->current_page <= 0 || $this->current_page > $this->total_pages ())
			$this->current_page = 1;
	}
	
	function offset ()
	{
		return ($this->current_page - 1) * $this->per_page;
	}
	
	function is_secondary_sort ()
	{
		return substr ($this->order_by, 0, 1) == '_' ? true : false;
	}
	
	function to_conditions ($conditions, $searches = '')
	{
		$sql = '';
		if ($conditions != '')
			$sql .= ' WHERE '.$conditions;
		
		// Add on search conditions
		if (is_array ($searches) && $this->search != '')
		{
			if ($sql == '')
				$sql .= 'WHERE (';
			else
				$sql .= ' AND (';

			$searchbits = array ();
			foreach ($searches AS $search)
				$searchbits[] = "$search LIKE \"%{$this->search}%\"";

			$sql .= implode (' OR ', $searchbits);
			$sql .= ')';
		}
		
		return $sql;
	}
	
	function to_limits ($conditions = '', $searches = '')
	{
		$sql = $this->to_conditions ($conditions, $searches);
		if (strlen ($this->order_by) > 0)
		{
			if (!$this->is_secondary_sort ())
				$sql .= " ORDER BY ".$this->order_by.' '.$this->order_direction;
			else
				$sql .= " ORDER BY ".$this->order_original.' '.$this->order_direction;
		}

		if ($this->per_page > 0)
			$sql .= ' LIMIT '.$this->offset ().','.$this->per_page;
		return $sql;
	}

	
	// Return the url with all the params added back
	function url ($offset, $orderby = '')
	{
		// Position
		if (strpos ($this->url, 'curpage=') !== false)
			$url = preg_replace ('/curpage=\d*/', 'curpage='.$offset, $this->url);
		else
			$url = $this->url.'&amp;curpage='.$offset;
			
		// Order
		if ($orderby != '')
		{
			if (strpos ($url, 'orderby=') !== false)
				$url = preg_replace ('/orderby=\w*/', 'orderby='.$orderby, $url);
			else
				$url = $url.'&amp;orderby='.$orderby;
			
			if ($this->order_by == $orderby)
				$dir = $this->order_direction == 'ASC' ? 'DESC' : 'ASC';
			else
				$dir = $this->order_direction;
				
			if (strpos ($url, 'order=') !== false)
				$url = preg_replace ('/order=\w*/', 'order='.$dir, $url);
			else
				$url = $url.'&amp;order='.$dir;
		}
		
		return str_replace ('&go=go', '', $url);
	}
	
	function current_page () { return $this->current_page; }
	
	function total_pages ()
	{
		if ($this->per_page == 0)
			return 1;
		return ceil ($this->total / $this->per_page);
	}
	
	function have_next_page ()
	{
		if ($this->current_page < $this->total_pages ())
			return true;
		return false;
	}
	
	function have_previous_page ()
	{
		if ($this->current_page > 1)
			return true;
		return false;
	}
	
	function sortable ($column, $text, $image = true)
	{
		$url = $this->url ($this->current_page, $column);
		if ($column == $this->order_by)
		{
			$dir = basename (dirname (dirname (__FILE__)));
			if (strpos ($url, 'ASC') !== false)
				$img = '<img align="bottom" src="'.get_bloginfo ('wpurl').'/wp-content/plugins/'.$dir.'/images/up.gif" alt="dir" width="16" height="7"/>';
			else
				$img = '<img align="bottom" src="'.get_bloginfo ('wpurl').'/wp-content/plugins/'.$dir.'/images/down.gif" alt="dir" width="16" height="7"/>';
				
			if ($image == false)
				$img = '';
		}
			
		return '<a href="'.$url.'">'.$text.'</a>'.$img;
	}
	
	function area_pages ()
	{
		// Returns an array of page numbers => link, given the current page (next and previous etc)
		// First page
		$allow_dot = true;
		$pages = array ();

		if ($this->total_pages () > 1)
		{
			$previous = __ ('Previous', 'redirection');
			$next     = __ ('Next', 'redirection');
			
			if ($this->have_previous_page ())
				$pages[] = '<a href="'.$this->url ($this->current_page - 1).'">'.$previous.'</a> |';
			else
				$pages[] = $previous.' |';
				
			for ($pos = 1; $pos <= $this->total_pages (); $pos++)
			{
				if ($pos == $this->current_page)
				{
					$pages[] = $pos;
					$allow_dot = true;
				}
				else if ($pos == 1 || abs ($this->current_page - $pos) <= 2 || $pos == $this->total_pages ())
					$pages[] = '<a href="'.$this->url ($pos).'">'.$pos."</a>";
				else if ($allow_dot)
				{
					$allow_dot = false;
					$pages[] = '&hellip;';
				}
			}
			
			if ($this->have_next_page ())
				$pages[] = '| <a href="'.$this->url ($this->current_page + 1).'">'.$next.'</a>';
			else
				$pages[] = '| '.$next;
		}

		return $pages;
	}
}

?>