<?php

namespace AntonioPrimera\AdminPanel;

use AntonioPrimera\AdminPanel\Http\Livewire\Dashboard;
use AntonioPrimera\AdminPanel\View\AdminPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class AdminPageManager
{
	protected static ?Collection $adminComponentClasses = null;
	
	/**
	 * Get an associative collection of Admin Pages [uid => ClassName]. If no
	 * Admin Pages are present, the default Admin Dashboard is shown.
	 *
	 * @return Collection
	 */
	public static function getAdminComponentClasses()
	{
		//if we have the result buffered, just return it
		if (static::$adminComponentClasses)
			return static::$adminComponentClasses;
		
		//$packageComponents = static::getClasses(
		//	static::adminPanelPackagePath('src/Http/Livewire'),
		//	'AntonioPrimera\\AdminPanel\\Http\\Livewire'
		//);
		
		$appComponents = static::getClasses(
			base_path(config('adminPanel.pages.folder')),
			config('adminPanel.pages.namespace')
		);
		
		//buffer the result, so we don't have to read the disk again next time
		static::$adminComponentClasses = $appComponents->isEmpty()
			? collect([Dashboard::getAdminPageUid() => Dashboard::class])//$packageComponents
			: $appComponents;
		
		return static::$adminComponentClasses;
	}
	
	/**
	 * A shortcut to get a collection of all admin page urls and the
	 * corresponding livewire component classes. This is used
	 * in generating the routes programmatically.
	 *
	 * Return format:
	 * [pageUrl => AdminPageClassName]
	 *
	 * @return Collection
	 */
	public static function getUrls()
	{
		$componentClasses = static::getAdminComponentClasses();
		
		return $componentClasses
			->sortBy(fn($className) => $className::$adminPagePosition)
			->mapWithKeys(
				function($className) {
					return [$className::getAdminPageUrl() => $className];
				}
			);
	}
	
	//--- Protected helpers -------------------------------------------------------------------------------------------
	
	///**
	// * Get the absolute path to an admin panel relative path. If no
	// * $path parameter is given, the absolute root path of
	// * the admin panel package is returned.
	// *
	// * @param string|null $path
	// *
	// * @return string
	// */
	//protected static function adminPanelPackagePath(?string $path = null)
	//{
	//	return rtrim(dirname(__DIR__), DIRECTORY_SEPARATOR)
	//		. DIRECTORY_SEPARATOR
	//		. ltrim($path ? $path : '', DIRECTORY_SEPARATOR);
	//}
	
	/**
	 * Get a collection of class names in the given folder, with the given namespace.
	 * Only classes which inherit the AdminPage Livewire Component are returned.
	 * The keys of the classes are the slugs of the component page names.
	 *
	 * @param string $folder
	 * @param string $namespace
	 *
	 * @return Collection
	 */
	protected static function getClasses(string $folder, string $namespace): Collection
	{
		if (!is_dir($folder))
			return collect();
		
		$files = File::allFiles($folder);
		
		$classes = collect();
		foreach ($files as $file) {
			if ($file->getExtension() !== 'php')
				continue;
			
			//try to guess the class name
			$className = "{$namespace}\\{$file->getFilenameWithoutExtension()}";
			
			//if this class exists and is a Livewire Component, add it to the class list
			if (is_subclass_of($className, AdminPage::class))
				$classes->put($className::getAdminPageUid(), $className);
		}
		
		return $classes;
	}
}