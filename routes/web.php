<?php

use AntonioPrimera\AdminPanel\Http\Controllers\AdminPanelController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('adminPanel.routePrefix'))
	->get('/', [AdminPanelController::class, 'index'])
	->middleware(config('adminPanel.middleware'))
	->name('admin-panel-dashboard');

Route::prefix(config('adminPanel.routePrefix'))
	->get('{url}', [AdminPanelController::class, 'show'])
	->middleware(config('adminPanel.middleware'))
	->name('admin-panel');
	