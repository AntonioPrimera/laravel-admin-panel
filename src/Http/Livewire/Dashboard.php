<?php

namespace AntonioPrimera\AdminPanel\Http\Livewire;

use AntonioPrimera\AdminPanel\View\AdminPage;

class Dashboard extends AdminPage
{
	protected static $adminPageName 	= 'Dashboard';
	protected static $adminPageUid      = 'admin-dashboard';
	protected static $adminMenuIcon 	= 'heroicon:home';
	protected static $adminPagePosition = -1;
	protected static $adminPageUrl		= '/';

    public function render()
    {
        return $this->adminPageView('admin-panel::livewire.pages.dashboard');
    }
}
