<?php

namespace AntonioPrimera\AdminPanel\Views;

use AntonioPrimera\AdminPanel\Facades\AdminPanel;
use Illuminate\View\Component;

/**
 * This component will render the configured admin panel layout dynamically, so you can use:
 *
 * <x-admin-panel-layout> ...your view here... </x-admin-panel-layout>
 * 							OR
 * <x-admin-page> ... your view here... </x-admin-page>
 */
class AdminPanelLayout extends Component
{
	public string|null $uid = null;
	
	public function __construct(string|null $uid = null)
	{
		$this->uid = $uid;
	}
	
	public function render()
	{
		if ($this->uid)
			AdminPanel::setCurrentPageUid($this->uid);
		
		return view(AdminPanel::getLayout()); //'<x-dynamic-component component="' . AdminPanel::getLayout() . '">{{ $slot }}</x-dynamic-component>';
	}
}