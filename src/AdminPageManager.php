<?php
namespace AntonioPrimera\AdminPanel;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;

class AdminPageManager
{
	const VIEW_TYPE_LIVEWIRE  = 'livewire';
	const VIEW_TYPE_BLADE     = 'blade';
	const VIEW_TYPE_INLINE    = 'inline';
	const VIEW_TYPE_UNDEFINED = 'undefined';
	
	protected ?Collection $pages = null;
	protected ?string $currentPageUid = null;
	
	public function getPages(): Collection
	{
		if (!$this->pages)
			$this->setupPageCollection();
		
		return $this->pages;
	}
	
	public function getPage(?string $uid): AdminPage|null
	{
		return $this->getPages()->first(fn(AdminPage $adminPage) => $adminPage->getUid() === $uid);
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
	
	/**
	 * Return the view to be included as an admin page in the admin panel.
	 * This can be used in any Controller, to render custom Livewire,
	 * Blade or inline views, wrapped in the admin-panel layout
	 */
	public function adminPageView(string $adminPageUid, string $view, array $viewData = [])
	{
		static::setCurrentPageUid($adminPageUid);
		
		return view(
			'admin-panel::admin-page',
			[
				'viewType' 		=> $this->getViewType($view),
				'view'			=> $this->getViewAlias($view),
				'viewData' 		=> $viewData,
			]
		);
	}
	
	/**
	 * Given a Livewire Component class name, a blade view name
	 * or an inline view, this method tries to determine
	 * which type of view it was given.
	 */
	public function getViewType(?string $view): string
	{
		if (!$view)
			return static::VIEW_TYPE_UNDEFINED;
		
		if (is_subclass_of($view, LivewireComponent::class))
			return static::VIEW_TYPE_LIVEWIRE;
		
		if (View::exists($view))
			return static::VIEW_TYPE_BLADE;
		
		return static::VIEW_TYPE_INLINE;
	}
	
	/**
	 * Get the view name / alias, which is necessary to render
	 * the actual view. This is necessary for Livewire
	 * components, which can have aliases.
	 */
	public function getViewAlias(?string $view): string|null
	{
		if ($this->getViewType($view) === static::VIEW_TYPE_LIVEWIRE)
			return Livewire::getAlias($view) ?: $view::getName();
		
		return $view;
	}
	
	/**
	 * Get the currently active admin page
	 */
	public function getCurrentPage(): AdminPage | null
	{
		return $this->currentPageUid
			? $this->getPage($this->currentPageUid)
			: $this->getPages()->first(fn(AdminPage $adminPage) => $adminPage->isActive());
	}
	
	public function setCurrentPageUid(string|null $uid): static
	{
		$this->currentPageUid = $uid;
		return $this;
	}
	
	public function getCurrentPageUid(): string | null
	{
		if (!$this->currentPageUid)
			$this->currentPageUid = $this->getCurrentPage()?->getUid();
		
		return $this->currentPageUid;
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
			->map(fn($attributes, $uid) => $this->instantiateAdminPage($attributes, $uid))
			->filter();
		
		$this->sortAdminPages();
	}
	
	protected function sortAdminPages()
	{
		$this->pages = $this->getPages()->sortBy(fn(AdminPage $adminPage) => $adminPage->getPosition());
	}
	
	protected function instantiateAdminPage($attributes, $uid): AdminPage | null
	{
		//an admin page must have at least one of these attributes configured: view, url or route
		if (!($attributes['view'] ?? $attributes['route'] ?? $attributes['url'] ?? false))
			return null;
		
		$pageUid = Str::kebab($attributes['uid'] ?? $uid);
		
		return new AdminPage(
			$attributes['name'],
			$pageUid,
			$attributes['icon'] ?? null,
			$attributes['menuLabel'] ?? null,
			$attributes['position'] ?? null,
			isset($attributes['route']) ? route($attributes['route']) : ($attributes['url'] ?? null),
			$attributes['view'] ?? null,
			$attributes['viewData'] ?? [],
			$attributes['hasRelatedPages'] ?? true,
		);
	}
	
	protected function determineCurrentPageUid(): string | null
	{
		$currentPage = $this->getCurrentPage();
		return $currentPage ? $currentPage->getUid() : null;
	}
}