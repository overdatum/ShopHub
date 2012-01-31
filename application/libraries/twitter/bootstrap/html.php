<?php namespace Twitter\Bootstrap;

//use \HTML;

/**
 * Form generation geared around Bootstrap from Twitter.
 *
 * @see http://twitter.github.com/bootstrap/
 */
use URL;
use Input;
class HTML extends \Laravel\HTML {

	public static function menu($list, $attributes = array())
	{
		$html = '';
		foreach ($list as $uri => $item)
		{
			if (isset($item['children']))
			{
				$html .= '
					<ul class="nav secondary-nav">
						<li class="dropdown">
							<a class="dropdown-toggle" href="/'.$uri.'">'.$item['name'].'</a>
							<ul class="dropdown-menu">';
				foreach ($item['children'] as $sub_uri => $sub_item)
				{
					$html .= '<li><a href="/'.$sub_uri.'">'.$sub_item['name'].'</a></li>';
				}
				$html .= '
							</ul>
						</li>
					</ul>';
			}
			else
			{
				$html .= '<li><a href="/'.$uri.'">'.$item['name'].'</a></li>';
			}
		}

		return '<ul class="nav">'.$html.'</ul>';
	}

	public static function link($url, $title, $attributes = array(), $https = false)
	{
		$url = static::entities(URL::to($url, $https));

		return '<a href="'.$url.'"'.static::attributes($attributes).'>'.$title.'</a>';
	}

	public static function sort_link($url, $sort_by, $name)
	{
		return HTML::link($url.'?'.http_build_query(array_merge(Input::all(), array('sort_by' => $sort_by, 'order' => (Input::get('sort_by') == $sort_by ? (Input::get('order') == 'ASC' ? 'DESC' : 'ASC') : 'ASC')))), $name);
	}

}