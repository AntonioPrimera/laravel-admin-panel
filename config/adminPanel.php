<?php

return [
	'layout' => 'admin-panel::livewire.layouts.admin-panel',
	
	//set to boolean false if the project does not use TailwindCss
	'projectTailwindCss' => 'css/app.css',
	
	'pages' => [
		//the app folder where the AdminPanel component classes are located
		'folder' 	=> 'app/Http/Livewire/AdminPanel',
		
		//the namespace of the AdminPage components
		'namespace' => 'App\\Http\\Livewire\\AdminPanel',
		
		//path relative to the project root
		'viewPath'  => 'resources/views',

		//the location of the blade files, relative to the viewPath setting above
		'bladePath' => 'livewire/admin-panel',
	],
	
	'routePrefix' => env('ADMIN_PANEL_ROUTE_PREFIX', 'admin-panel'),
	'middleware'  => ['web', 'auth'],
	
	//'routes' => [
	//	'prefix' 		 => env('ADMIN_PANEL_ROUTE_PREFIX', 'admin-panel'),
	//
	//	//todo: document this
	//	'middleware'	 => ['web', 'auth'],
	//],
];