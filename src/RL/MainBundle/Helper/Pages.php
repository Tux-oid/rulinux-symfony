<?php
/**
 * @author Tux-oid 
 */

namespace RL\MainBundle\Helper;

class Pages
{
	protected $itemsOnPage;
	protected $itemsCount;
	protected $page;
	protected $router;
	protected $routeName;
	protected $routeParameters;
	public function draw()
	{
		$itemsOnPage = $this->itemsOnPage;
		$page = $this->page;
		$pagesCount = ceil(($this->itemsCount) / $itemsOnPage);
		$pages = '';
		if($pagesCount > 1)
		{
			if($page > 1)
			{
				$pg = $page - 1;
				$this->routeParameters['page'] = 1;
				$pages = $pages.'<a href="'.$this->router->generate($this->routeName, $this->routeParameters).'" title="First page">←</a>&nbsp;';
				$this->routeParameters['page'] = $pg;
				$pages = $pages.'<a href="'.$this->router->generate($this->routeName, $this->routeParameters).'" title="Backward">≪</a>&nbsp;';
			}
			if($pagesCount > 10)
			{
				if($page < 5)
					$startPage = 1;
				else
					$startPage = $page - 4;

				if($page > $pagesCount - 4)
					$endPage = $pagesCount;
				else
					$endPage = $page + 4;
				for($p = $startPage; $p <= $endPage; $p++)
				{
					$this->routeParameters['page'] = $p;
					if($p == $page)
						$pages = $pages.'<b>'.($p).'</b>&nbsp;';
					else
						$pages = $pages.'<a href="'.$this->router->generate($this->routeName, $this->routeParameters).'" title="Page №'.$p.'">'.($p).'</a>&nbsp;';
				}
			}
			else
			{
				for($p = 1; $p <= $pagesCount; $p++)
				{
					$this->routeParameters['page'] = $p;
					if($p == $page)
						$pages = $pages.'<b>'.($p).'</b>&nbsp;';
					else
						$pages = $pages.'<a href="'.$this->router->generate($this->routeName, $this->routeParameters).'" title="Page №'.$p.'">'.($p).'</a>&nbsp;';
				}
			}
			if($page < $pagesCount)
			{
				$pg = $page + 1;
				$this->routeParameters['page'] = $pg;
				$pages = $pages.'<a href="'.$this->router->generate($this->routeName, $this->routeParameters).'" title="Forward">≫</a>&nbsp;';
				$this->routeParameters['page'] = $pagesCount;
				$pages = $pages.'<a href="'.$this->router->generate($this->routeName, $this->routeParameters).'" title="End page">→</a>&nbsp;';
			}
			$pages = '<div class="pages">'.$pages.'</div>';
			return $pages;
		}
		else
			return '';
	}
	function __construct($router, $itemsOnPage, $itemsCount, $page, $routeName, array $parameters)
	{
		$this->itemsOnPage = $itemsOnPage;
		$this->itemsCount = $itemsCount;
		$this->page = $page;
		$this->router = $router;
		$this->routeName = $routeName;
		$this->routeParameters = $parameters;
		
	}
	public function getItemsOnPage()
	{
		return $this->itemsOnPage;
	}
	public function setItemsOnPage($itemsOnPage)
	{
		$this->itemsOnPage = $itemsOnPage;
	}
	public function getItemsCount()
	{
		return $this->itemsCount;
	}
	public function setItemsCount($itemsCount)
	{
		$this->itemsCount = $itemsCount;
	}
	public function getPage()
	{
		return $this->page;
	}
	public function setPage($page)
	{
		$this->page = $page;
	}
	public function getRouter()
	{
		return $this->router;
	}
	public function setRouter($router)
	{
		$this->router = $router;
	}
	public function getRouteName()
	{
		return $this->routeName;
	}
	public function setRouteName($routeName)
	{
		$this->routeName = $routeName;
	}
	public function getRouteParameters()
	{
		return $this->routeParameters;
	}
	public function setRouteParameters($routeParameters)
	{
		$this->routeParameters = $routeParameters;
	}



}
?>
