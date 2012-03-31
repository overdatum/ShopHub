<?php

use Laravel\HTML;

class Menu {

	public static $containers;

	static function container($containers = '', $prefix_links = false)
	{
		if( ! is_array($containers)) $containers = array($containers);

		foreach ($containers as $container)
		{
			if( ! isset(static::$containers[$container]))
			{
				static::$containers[$container] = new MenuItems($container, $prefix_links);
			}
		}
		
		return new MenuGroup($containers);
	}

	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::container(), $method), $parameters);
	}

}

class MenuGroup {

	public $containers;

	public function __construct($containers)
	{
		$this->containers = $containers;
	}

	public function __call($method, $parameters)
	{
		foreach($this->containers as $index => $container)
		{
			Menu::$containers[$container] = call_user_func_array(array(Menu::$containers[$container], $method), $parameters);
		}

		return $this;
	}

	public function __toString()
	{
		$html = '';
		foreach($this->containers as $container)
		{
			$html .= Menu::$containers[$container]->render();
		}

		return $html;
	}

}

class MenuItems {

	public $items;
	
	public $container;
	
	public $prefix_links;
	
	public function __construct($container = '', $prefix_links = false)
	{
		$this->container = $container;
		$this->prefix_links = $prefix_links;
	}
	
	public static function factory()
	{
		return new MenuItems;
	}
	
	public function add($url, $title, $attributes = array(), $children = null)
	{
		if(is_null($children))
		{
			$children = new MenuItems;
		}
		
		$this->items[] = array(
			'url' => $url,
			'title' => $title,
			'attributes' => $attributes,
			'children' => $children
		);
		
		return $this;
	}

	public function attach($menuitems)
	{
		$this->items = array_merge($this->items, $menuitems->items);
	}

	public function find($url)
	{
		foreach($this->items as $item)
		{
			if($item['url'] == $url) return $item['children'];
			
			$search = $item['children']->find($url);
			
			if( ! is_null($search))
			{
				return $search;
			}
		}
		
		throw new \Exception('Unable to locate the menu item "' . $url . '"');
	}
	
	public function render($list_attributes = array(), $link_attributes = array(), $items = null)
	{
		if(is_null($items)) $items = $this->items;
		
		$menu_items = array();
		foreach($items as $item)
		{
			$menu_item = HTML::link(($this->prefix_links ? $this->container . '/' : '') . $item['url'], $item['title'], $link_attributes);
			if( ! is_null($item['children']->items))
			{
				$menu_item .= $this->render($ul_attributes, $link_attributes, $item['children']->items);
			}
			$menu_items[] = $menu_item;
		}
		
		return HTML::ul($menu_items, $list_attributes);
	}

}