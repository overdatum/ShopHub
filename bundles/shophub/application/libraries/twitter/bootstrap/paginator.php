<?php namespace Twitter\Bootstrap;

use Laravel\URI;
use Laravel\Request;
use Laravel\Lang;

class Paginator extends \Laravel\Paginator {

	/**
	 * Create the HTML pagination links.
	 *
	 * Typically, an intelligent, "sliding" window of links will be rendered based
	 * on the total number of pages, the current page, and the number of adjacent
	 * pages that should rendered. This creates a beautiful paginator similar to
	 * that of Google's.
	 *
	 * Example: 1 2 ... 23 24 25 [26] 27 28 29 ... 51 52
	 *
	 * If you wish to render only certain elements of the pagination control,
	 * explore some of the other public methods available on the instance.
	 *
	 * <code>
	 *		// Render the pagination links
	 *		echo $paginator->links();
	 *
	 *		// Render the pagination links using a given window size
	 *		echo $paginator->links(5);
	 * </code>
	 *
	 * @param  int     $adjacent
	 * @return string
	 */
	public function links($adjacent = 3)
	{
		if ($this->last <= 1) return '';
		// The hard-coded seven is to account for all of the constant elements in a
		// sliding range, such as the current page, the two ellipses, and the two
		// beginning and ending pages.
		//
		// If there are not enough pages to make the creation of a slider possible
		// based on the adjacent pages, we will simply display all of the pages.
		// Otherwise, we will create a "truncating" slider which displays a
		// nice window of pages based on the current page.
		if ($this->last < 7 + ($adjacent * 2))
		{
			$links = $this->range(1, $this->last);
		}
		else
		{
			$links = $this->slider($adjacent);
		}

		$content = $this->previous().' '.$links.' '.$this->next();

		return '<ul class="pagination">'.$content.'</ul>';
	}

	/**
	 * Create a chronological pagination element, such as a "previous" or "next" link.
	 *
	 * @param  string   $element
	 * @param  int      $page
	 * @param  string   $text
	 * @param  Closure  $disabled
	 * @return string
	 */
	protected function element($element, $page, $text, $disabled)
	{
		$class = "{$element}_page";

		if (is_null($text))
		{
			$text = Lang::line("pagination.{$element}")->get($this->language);
		}

		// Each consumer of this method provides a "disabled" Closure which can
		// be used to determine if the element should be a span element or an
		// actual link. For example, if the current page is the first page,
		// the "first" element should be a span instead of a link.
		if ($disabled($this->page, $this->last))
		{
			return '<li class="disabled">'.HTML::span($text, array('class' => "{$class} disabled")).'</li>';
		}
		else
		{
			return $this->link($page, $text, $class);
		}
	}

	/**
	 * Build a range of numeric pagination links.
	 *
	 * For the current page, an HTML span element will be generated instead of a link.
	 *
	 * @param  int     $start
	 * @param  int     $end
	 * @return string
	 */
	protected function range($start, $end)
	{
		$pages = array();

		// To generate the range of page links, we will iterate through each page
		// and, if the current page matches the page, we will generate a span,
		// otherwise we will generate a link for the page. The span elements
		// will be assigned the "current" CSS class for convenient styling.
		for ($page = $start; $page <= $end; $page++)
		{
			if ($this->page == $page)
			{
				$pages[] = '<li class="disabled">'.HTML::span($page, array('class' => 'current')).'</li>';
			}
			else
			{
				$pages[] = $this->link($page, $page, null);
			}
		}

		return implode(' ', $pages);
	}

	/**
	 * Create a HTML page link.
	 *
	 * @param  int     $page
	 * @param  string  $text
	 * @param  string  $attributes
	 * @return string
	 */
	protected function link($page, $text, $class)
	{
		$query = '?page='.$page.$this->appendage($this->appends);

		return '<li>'.HTML::link(URI::current().$query, $text, compact('class'), Request::secure()).'</li>';
	}

}