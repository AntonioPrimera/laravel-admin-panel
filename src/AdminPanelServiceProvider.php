<?php

namespace AntonioPrimera\AdminPanel;

//use Spatie\LaravelPackageTools\PackageServiceProvider;
//use Spatie\LaravelPackageTools\Package;

use AntonioPrimera\AdminPanel\Console\Commands\MakeAdminPage;
use AntonioPrimera\AdminPanel\Http\Livewire\Dashboard;
use AntonioPrimera\AdminPanel\View\Components\Layout;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AdminPanelServiceProvider extends ServiceProvider
{
	
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/adminPanel.php', 'adminPanel'
		);
	}
	
	public function boot()
	{
		//register our view macros
		View::mixin(new AdminPanelViewMacros);
		
		//config files
		$this->publishes([
			__DIR__ . '/../config/adminPanel.php' => config_path('adminPanel.php'),
		], 'admin-panel-config');
		
		//routes
		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		
		//views
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin-panel');
		
		//view components
		$this->loadViewComponentsAs('admin-panel', [
			Layout::class,		//admin-panel-layout
			Dashboard::class,	//admin-panel-dashboard
		]);
		
		if ($this->app->runningInConsole()) {
			$this->commands([
				MakeAdminPage::class,
				//InstallCommand::class,
				//NetworkCommand::class,
			]);
		}
	}
	
	//public function configurePackage(Package $package): void
	//{
	//	$package
	//		->name('antonioprimera/laravel-admin-panel')
	//		->hasConfigFile('adminPanel')
	//		->hasViews()
	//		->hasViewComponent('spatie', Alert::class)
	//		//->hasViewComposer('*', MyViewComposer::class)
	//		//->sharesDataWithAllViews('downloads', 3)
	//		//->hasTranslations()
	//		//->hasAssets()
	//		//->hasRoute('web')
	//		//->hasMigration('create_package_tables')
	//		//->hasCommand(YourCoolPackageCommand::class)
	//	;
	//}
}