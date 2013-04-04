<?php
class AdminPage
{
	public function getPage($tabel)
	{
		switch($tabel)
		{
			case 'content' : return 'manage_pages';
			case 'menu' : return 'manage_menu' ;
			case 'users' : return 'manage_accounts';
		}
	}


}