<?php

namespace AntonioPrimera\AdminPanel\Tests\Unit;

use AntonioPrimera\AdminPanel\Tests\TestCase;
use AntonioPrimera\Testing\CustomAssertions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeAdminPageCommandTest extends TestCase
{
	use CustomAssertions;
	
	protected function setUp(): void
	{
		parent::setUp();
		$this->cleanup();
	}
	
	protected function tearDown(): void
	{
		parent::tearDown();
		$this->cleanup();
	}
	
	//--- Actual tests ------------------------------------------------------------------------------------------------
	
	/** @test */
	public function it_can_create_a_new_admin_panel_livewire_component()
	{
		$this->assertAdminPageFilesDoNotExist();
		
		Artisan::call('make:admin-page MyAdminPage');
		
		$this->assertAdminPageFilesExist();
	}
	
	//--- Protected helpers -------------------------------------------------------------------------------------------
	
	protected function assertAdminPageFilesDoNotExist($name = 'MyAdminPage')
	{
		$this->assertFileDoesNotExist($this->componentPath($name));
		$this->assertFileDoesNotExist($this->bladePath($name));
	}
	
	protected function assertAdminPageFilesExist($name = 'MyAdminPage')
	{
		$this->assertFilesExist([
			$this->componentPath($name),
			$this->bladePath($name)
		]);
	}
	
	protected function componentPath($name)
	{
		return app_path("Http/Livewire/AdminPanel/$name.php");
	}
	
	protected function bladePath($name)
	{
		return resource_path("views/livewire/admin-panel/" . Str::kebab($name) . ".blade.php");
	}
	
	protected function cleanup($name = 'MyAdminPage')
	{
		$files = [
			$this->componentPath($name),
			$this->bladePath($name)
		];
		
		foreach ($files as $file) {
			if (file_exists($file))
				unlink($file);
		}
	}
}