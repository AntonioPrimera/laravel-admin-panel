<?php

use AntonioPrimera\AdminPanel\AdminPageManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//dump(config('adminPanel.routes.prefix', ''));
Route::middleware(config('adminPanel.routes.middleware', 'web'))
	->group(function () {
		//Route::view('/', 'admin-panel::admin-panel');
		//Route::get('admin-panel/', \AntonioPrimera\AdminPanel\Http\Livewire\Dashboard::class);
		
		foreach (AdminPageManager::getUrls() as $url => $className) {
			Route::get($url, '\\' . $className)->middleware([]);
		}
	});
