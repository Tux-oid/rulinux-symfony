<?php
/**
 * @author Tux-oid 
 */

namespace RL\MainBundle\Form;

class Pages
{
	protected $itemsOnPage;
	protected $itemsCount;
	protected $page;
	public function draw()
	{
		$itemsOnPage = $this->itemsOnPage;
		$page = $this->page;
		$pagesCount = ceil(($this->itemsCount - 1) / $itemsOnPage);
		$pages = '';
		if($pagesCount > 1)
		{
			if($page > 1)
			{
				$pg = $page - 1;
				$pages = $pages.'<a href="thread_page_1" title="В начало">←</a>&nbsp;';
				$pages = $pages.'<a href="thread_page_'.$pg.'" title="Назад">≪</a>&nbsp;';
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
					if($p == $page)
						$pages = $pages.'<b>'.($p).'</b>&nbsp;';
					else
						$pages = $pages.'<a href="thread_page_'.$p.'" title="Страница №'.$p.'">'.($p).'</a>&nbsp;';
				}
			}
			else
			{
				for($p = 1; $p <= $pagesCount; $p++)
				{
					if($p == $page)
						$pages = $pages.'<b>'.($p).'</b>&nbsp;';
					else
						$pages = $pages.'<a href="thread_page_'.$p.'" title="Страница №'.$p.'">'.($p).'</a>&nbsp;';
				}
			}
			if($page < $pagesCount)
			{
				$pg = $page + 1;
				$pages = $pages.'<a href="thread_page_'.$pg.'" title="Вперед">≫</a>&nbsp;';
				$pages = $pages.'<a href="thread_page_'.$pagesCount.'" title="В конец">→</a>&nbsp;';
			}
			return $pages;
		}
		else
			return '';
	}
	function __construct($itemsOnPage, $itemsCount, $page)
	{
		$this->itemsOnPage = $itemsOnPage;
		$this->itemsCount = $itemsCount;
		$this->page = $page;
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


}
?>
