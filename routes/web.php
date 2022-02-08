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

//dump(config('adminPanel.routePrefix', ''));
Route::middleware(config('adminPanel.middleware', 'web'))
	->group(function () {
		//Route::view('/', 'admin-panel::admin-panel');
		//Route::get('admin-panel/', \AntonioPrimera\AdminPanel\Http\Livewire\Dashboard::class);
		$urls = AdminPageManager::getUrls();
		
		//try to find a root url => component
		$root = $urls->get('/');
		
		//make sure there is a root component with a named route "admin-panel"
		if ($root) {
			$routePrefix = trim(config('adminPanel.routePrefix'), '/');
			Route::get('/' . $routePrefix, '\\' . $root)->name('admin-panel');
		} else {
			//take the first route and make it root
			$first = $urls->take(1);
			$firstUrl = $first->keys()->first();
			$firstClassName = $first->values()->first();
			Route::get($firstUrl, '\\' . $firstClassName)->name('admin-panel');
			
			$urls->shift();
		}
		
		////if we don't have root admin page (url = '/') set the first admin page as root
		//if (!$urls->first(fn($className, $url) => $url === '/')) {
		//	Route::get(rtrim(config('adminPanel.routePrefix'), '/') . '/', '\\' . $urls->first())->name('admin-panel');
		//}
		
		//create a route for each url
		foreach ($urls as $url => $className) {
			Route::get($url, '\\' . $className);
		}
	});
