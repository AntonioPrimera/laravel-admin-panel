<?php

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

//Route::get('admin-panel/', \AntonioPrimera\AdminPanel\Http\Livewire\Dashboard::class);

//dump(config('adminPanel.routes.prefix', ''));
//Route::
	//->middleware('auth')
	//->group(function () {
		//Route::view('/', 'admin-panel::admin-panel');
		//Route::get('admin-panel/', \AntonioPrimera\AdminPanel\Http\Livewire\Dashboard::class);
		
		foreach (\AntonioPrimera\AdminPanel\AdminPageManager::getUrls() as $url => $className) {
			Route::get($url, '\\' . $className)->middleware([]);
		}
	//});
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');
//
//require __DIR__.'/auth.php';
