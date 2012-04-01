<?php

use Laravel\HTML;

class Menu {

	public static $containers = array();

	public $handles = array();

	public function __construct($handles)
	{
		$this->handles = $handles;
	}

	public function __call($method, $parameters)
	{
		foreach ($this->handles as $container)
		{
			static::$containers[$container] = call_user_func_array(array(static::$containers[$container], $method), $parameters);
		}

		return $this;
	}

	public function render($list_attributes = array(), $link_attributes = array())
	{
		$html = '';
		foreach($this->handles as $container)
		{
			$html .= static::$containers[$container]->render($list_attributes, $link_attributes);
		}

		return $html;
	}

	public function __toString()
	{
		return $this->render();
	}

	public static function container($containers = '', $prefix_links = false)
	{
		if( ! is_array($containers)) $containers = array($containers);

		foreach ($containers as $container)
		{
			if( ! array_key_exists($container, static::$containers))
			{
				static::$containers[$container] = new MenuItems($container, $prefix_links);
			}
		}

		return new Menu($containers);
	}

	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::container(), $method), $parameters);
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
	
	public function render($list_attributes = array(), $link_attributes = array(), $items = null)
	{
		if(is_null($items)) $items = $this->items;
		if(is_null($items)) return '';

		$menu_items = array();
		foreach($items as $item)
		{
			$menu_item = HTML::link(($this->prefix_links ? (gettype($this->prefix_links) == 'string' ? $this->prefix_links : $this->container) . '/' : '') . $item['url'], $item['title'], $link_attributes);
			if( ! is_null($item['children']))
			{
				$menu_item .= $this->render($list_attributes, $link_attributes, $item['children']->items);
			}
			$menu_items[] = $menu_item;
		}
		
		return HTML::ul($menu_items, $list_attributes);
	}

}