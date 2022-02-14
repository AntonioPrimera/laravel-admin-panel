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
		
		////try to find the root item [url => componentClassName]
		//$rootUrl = '/' . trim(config('adminPanel.routePrefix'), '/');
		//$rootComponentClass = $urls->get($rootUrl);
		//
		//if ($rootComponentClass) {
		//	Route::get($rootUrl, '\\' . $rootComponentClass)->name('admin-panel');
		//	$urls = $urls->filter(fn($componentClass, $url) => $url !== '/');
		//} else {
		//	//redirect from the root to the
		//	//$first = $urls->take(1);
		//	$firstUrl = $urls->take(1)->keys()->first();
		//	//$firstClassName = $first->values()->first();
		//	//Route::get($firstUrl, '\\' . $firstClassName)->name('admin-panel');
		//	Route::redirect($rootUrl, $firstUrl)->name('admin-panel');
		//
		//	//$urls->shift();
		//}
		
		////if we don't have root admin page (url = '/') set the first admin page as root
		//if (!$urls->first(fn($className, $url) => $url === '/')) {
		//	Route::get(rtrim(config('adminPanel.routePrefix'), '/') . '/', '\\' . $urls->first())->name('admin-panel');
		//}
		
		/*
		 * Create a route for each url. If we have a root url for the admin panel (e.g. '/admin-panel') set the route
		 * name 'admin-panel', otherwise set the name of the first route to 'admin-panel'. This way we always have a
		 * route named 'admin-panel' and we can address this route as the entry point to the admin panel.
		 */
		$hasAdminPanelRoot = false;
		$firstRoute = null;
		$routePrefix = trim(config('adminPanel.routePrefix'), '/');
		
		//create a route for each url
		foreach ($urls as $url => $className) {
			$route = Route::get($url, '\\' . $className);
			
			if (!$firstRoute)
				$firstRoute = $route;
			
			if (trim($url, '/') === $routePrefix) {
				$route->name('admin-panel');
				$hasAdminPanelRoot = true;
			}
		}
		
		if (!$hasAdminPanelRoot && $firstRoute)
			$firstRoute->name('admin-panel');
	});
