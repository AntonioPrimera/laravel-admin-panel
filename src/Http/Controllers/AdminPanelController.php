<?php

namespace AntonioPrimera\AdminPanel\Http\Controllers;

use AntonioPrimera\AdminPanel\Facades\AdminPanel;
use Illuminate\Routing\Controller;

class AdminPanelController extends Controller
{
	
	public function show(string $url)
	{
		$adminPage = AdminPanel::getPageByUrl($url);
		$layout = AdminPanel::getLayout();
		$uid = $adminPage->getUid();
		
		return view('admin-panel::admin-page', compact('uid', 'adminPage', 'layout'));
	}
	
	public function index()
	{
		$layout = AdminPanel::getLayout();
		$adminPages = AdminPanel::getPages();
		
		return view('admin-panel::dashboard', compact('layout', 'adminPages'));
	}
}