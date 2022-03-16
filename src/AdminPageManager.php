<?php
namespace AntonioPrimera\AdminPanel;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class AdminPageManager
{
	protected ?Collection $pages = null;
	
	public function getPages(): Collection
	{
		if (!$this->pages)
			$this->setupPageCollection();
		
		return $this->pages;
	}
	
	public function getPage(string $uid): AdminPage|null
	{
		return $this->getPages()->first(fn(AdminPage $adminPage) => $adminPage->getUid() === $uid);
	}
	
	public function getPageByUrl(string $url)
	{
		return $this->getPages()->first(fn(AdminPage $adminPage) => $adminPage->getRawUrl() === $url);
	}
	
	public function getLayout(): string
	{
		return config('adminPanel.layout', 'admin-panel::layouts.default');
	}
	
	public function addPage(AdminPage $adminPage)
	{
		$this->getPages()
			->put($adminPage->getUid(), $adminPage);
		
		$this->sortAdminPages();
	}
	
	public function addPages(Collection $pages)
	{
		$this->pages = $this->getPages()
			->merge($pages);
		
		$this->sortAdminPages();
	}
	
	//--- Protected helpers -------------------------------------------------------------------------------------------
	
	protected function setupPageCollection()
	{
		$adminPageCollectors = App::tagged('admin-pages');
		
		$adminPages = [];
		
		foreach ($adminPageCollectors as $adminPageCollector) {
			if (is_callable([$adminPageCollector, 'resolve']))
				$adminPages = array_merge($adminPages, $adminPageCollector->resolve());
		}
		
		$this->pages = Collection::wrap($adminPages)
			//->sortBy('position')
			->map(fn($attributes, $uid) => $this->instantiateAdminPage($attributes, $uid));
		
		$this->sortAdminPages();
	}
	
	protected function sortAdminPages()
	{
		$this->pages = $this->getPages()->sortBy(fn(AdminPage $adminPage) => $adminPage->getPosition());
	}
	
	protected function instantiateAdminPage($attributes, $uid)
	{
		$pageUid = Str::kebab($attributes['uid'] ?? $uid);
		
		return new AdminPage(
			$attributes['name'],
			$pageUid,
			$attributes['icon'] ?? null,
			$attributes['menuLabel'] ?? null,
			$attributes['position'] ?? null,
			$attributes['url'] ?? $pageUid,
			$attributes['view'] ?? null,
			$attributes['viewData'] ?? []
		);
	}
}