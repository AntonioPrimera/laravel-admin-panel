<?php

namespace AntonioPrimera\AdminPanel\Console\Commands;

use AntonioPrimera\Artisan\FileGeneratorCommand;
use AntonioPrimera\Artisan\FileRecipe;
use Illuminate\Support\Str;

class MakeAdminPage extends FileGeneratorCommand
{
	protected $signature = 'make:admin-page
								{name : the name of the target file (can be nested)}
								{--dry-run : if set, the files are not created}
							';
	
	protected $description = 'Create a new admin page for the admin panel';
	
	protected function recipe(): array
	{
		//create the component file ingredient
		$componentRecipe = new FileRecipe(
			__DIR__ . '/stubs/AdminPageComponent.php.stub',
			base_path(config('adminPanel.pages.folder'))
		);
		$componentRecipe->rootNamespace = trim(config('adminPanel.pages.namespace'), '\\');
		$componentRecipe->replace = [
			'DUMMY_BLADE_REFERENCE' => $this->getBladeReference()
		];
		
		//create the blade file ingredient
		$bladeRecipe = new FileRecipe(
			__DIR__ . '/stubs/admin-page.blade.php.stub',
			trim(config('adminPanel.pages.bladePath'), '/\\')
		);
		$bladeRecipe->rootPath = base_path(config('adminPanel.pages.viewPath'));
		$bladeRecipe->fileNameFormat = 'kebab';
		
		//mix the ingredients into the final recipe
		return [
			'Component File' => $componentRecipe,
			'Blade File' 	 => $bladeRecipe,
		];
	}
	
	/**
	 * Create a dot separated reference to the blade file,
	 * used with the "view($reference)" Laravel helper
	 *
	 * @return string
	 */
	protected function getBladeReference()
	{
		$kebabNameParts = Str::of($this->getNameArgument())
			->replace(['/', '\\'], '|')
			->explode('|')
			->map(function($part){ return Str::kebab($part); })
			->implode('.');
			
		
		return str_replace('/', '.', config('adminPanel.pages.bladePath'))
			. '.'
			. $kebabNameParts;
	}
}