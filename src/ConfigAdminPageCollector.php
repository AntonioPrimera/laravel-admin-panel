<?php

namespace AntonioPrimera\AdminPanel;

use Illuminate\Support\Collection;

class ConfigAdminPageCollector
{
	public function resolve()
	{
		return config('adminPanel.pages', []);
	}
}