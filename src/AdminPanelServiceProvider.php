<?php
namespace AntonioPrimera\AdminPanel;

use Illuminate\Support\ServiceProvider;

class AdminPanelServiceProvider extends ServiceProvider
{
	
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/adminPanel.php', 'adminPanel'
		);
		
		$this->app->singleton(AdminPageManager::class);
		
		//register and tag the default Admin Page Collector (collects admin pages from the adminPanel.pages config)
		$this->app->bind(ConfigAdminPageCollector::class);
		$this->app->tag(ConfigAdminPageCollector::class, 'admin-pages');
	}
	
	public function boot()
	{
		//config files
		$this->publishes([
			__DIR__ . '/../config/adminPanel.php' => config_path('adminPanel.php'),
		], 'admin-panel-config');
		
		//routes
		$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		
		//views
		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin-panel');
	}
}