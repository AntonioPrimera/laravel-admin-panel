<?php

return [
	'layout' => 'admin-panel::layouts.default',
	
	//set to boolean false if the project does not use TailwindCss
	'projectTailwindCss' => 'css/app.css',
	
	'routePrefix' => env('ADMIN_PANEL_ROUTE_PREFIX', 'admin-panel'),
	'middleware'  => explode(',', env('ADMIN_PANEL_MIDDLEWARE', 'web,auth')),
	
	'pages' => [
		//add all your admin pages here
		//'gallery' => [
		//	'name' 	=> 'Gallery',				//the name / title of the admin page
		//	'icon' 	=> 'heroicon:photograph',	//the heroicon name (prefixed with 'hero:' or 'heroicon:')
		//	'menuLabel' => null, 				//the label in the menu (by default the admin page name is used)
		//	'position' => 0,					//the position in the menu
		//	'uid' => null,						//by default, the uid is determined dynamically (used in the route)
		//	'view' => LivewireComponentClass::class, // 'blade.component' // '<div>Inline view</div>
		//],
	],
];