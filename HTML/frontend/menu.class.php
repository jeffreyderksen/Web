<?php
include_once ('content_part.class.php');

class Menu extends ContentPart
{
	private $menuItems;
	
	public function __construct($menuItems) //dubbele underscore vanwege code in de constructor
	{
		$this->menuItems = $menuItems;
	}
	
	public function render()
	{
		$result = '';
		$result .= '<ul>';
		
		foreach ($this->menuItems as &$menuItem)
		{
			$result .= '<li>';
			$result .= '<a href="?page='.strtolower($menuItem).'">'.$menuItem.'</a>';
			$result .= '</li>';
		}
		
		$result .= '</ul>';
		
		return $result;
	}
	
}

?>