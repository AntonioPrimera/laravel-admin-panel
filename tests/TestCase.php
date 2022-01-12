<?php

namespace AntonioPrimera\AdminPanel\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class TestCase extends \Orchestra\Testbench\TestCase
{
	//use RefreshDatabase;
	
	protected function setUp(): void
	{
		parent::setUp();
	}
	
	protected function getPackageProviders($app)
	{
		return [
			\AntonioPrimera\AdminPanel\AdminPanelServiceProvider::class,
		];
	}
}