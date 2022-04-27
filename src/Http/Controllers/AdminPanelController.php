<?php

namespace AntonioPrimera\AdminPanel\Http\Controllers;

use AntonioPrimera\AdminPanel\Facades\AdminPanel;
use Illuminate\Routing\Controller;

class AdminPanelController extends Controller
{
	
	public function show(string $uid)
	{
		$adminPage = AdminPanel::getPage($uid);
		
		//todo: make a nice 404 page inside the admin-panel layout
		if (!$adminPage)
			abort(404);
		
		AdminPanel::setCurrentPageUid($uid);
		
		return AdminPanel::adminPageView(
			$uid,
			$adminPage->getRawView(),
			$adminPage->getViewData(),
			$adminPage->getName() ?: 'Dashboard'
		);
		
		//return view('admin-panel::admin-page', compact('uid', 'adminPage', 'layout'));
	}
	
	public function index()
	{
		$layout = AdminPanel::getLayout();
		$adminPages = AdminPanel::getPages();
		
		return view('admin-panel::dashboard', compact('layout', 'adminPages'));
	}
}