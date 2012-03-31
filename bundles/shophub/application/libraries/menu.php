<?php

use Laravel\HTML;
use Exception;

class Menu {

    public static $containers;

    static function container($container = '', $prefix_links = false)
    {
        if( ! isset(static::$containers[$container]))
        {
            static::$containers[$container] = new MenuItems($container, $prefix_links);
        }
        
        return static::$containers[$container];
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
        
        throw new Exception('Unable to locate the menu item "' . $url . '"');
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
    
    public function __toString()
    {
        return $this->render();
    }

}