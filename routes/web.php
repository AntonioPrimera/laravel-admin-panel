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
		$urls = AdminPageManager::getUrls();
		
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
		
		if (!$hasAdminPanelRoot && $firstRoute) {
			$firstRoute->name('admin-panel');
			//also add a redirect route from the root admin-panel url to the url of the first admin page
			Route::redirect("/$routePrefix", '/' . ltrim($firstRoute->uri(), '/'));
		}
	});
