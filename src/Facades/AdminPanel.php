<?php

namespace AntonioPrimera\AdminPanel\Facades;

use AntonioPrimera\AdminPanel\AdminPage;
use AntonioPrimera\AdminPanel\AdminPageManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection getPages()
 * @method static AdminPage|null getPage(string $uid)
 * @method static AdminPage|null getPageByUrl(string $url)
 * @method static string getLayout()
 * @method static addPage(AdminPage $adminPage)
 * @method static addPages(Collection $pages)
 * @method static string getViewType(string $view)
 * @method static adminPageView(string $adminPageUid, string $view, array $viewData = [], ?string $pageTitle = null)
 * @method static string|null getView(?string $view)
 */
class AdminPanel extends Facade
{
	protected static function getFacadeAccessor()
	{
		return AdminPageManager::class;
	}
}