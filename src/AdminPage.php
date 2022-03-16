<?php
namespace AntonioPrimera\AdminPanel;

use AntonioPrimera\HeroIcons\HeroIcon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;

class AdminPage
{
	
	/**
	 * The label of this page in the menu. If this
	 * is not set, the adminPageName is used.
	 */
	protected string $menuLabel;
	
	/**
	 * The title of the admin page
	 */
	protected string $name;
	
	/**
	 * This is used as the key for the page. If null,
	 * it defaults to the slug of $adminPageName
	 */
	protected string $uid;
	
	/**
	 * Optionally provide a string icon (the svg as a string) or use a hero icon.
	 *
	 * If you want to use a hero icon, the format for this attribute is:
	 * <heroicon|hero>:<name of the icon>:<outline|solid>:<optionally a class string to be set on the svg>
	 *
	 * By default, outline icons are used.
	 *
	 * e.g. "heroicon:home"
	 * 		"hero:home"
	 * 		"hero:home:outline"
	 * 		"heroicon:home:solid:w-6 h-6"
	 *
	 * The first three examples above, generate the same result.
	 */
	protected string $icon;
	
	/**
	 * Override this if you want to have a specific url for this page.
	 * If not set, the page uid will be used as the url.
	 */
	protected string $url;
	
	/**
	 * The order in which it should appear in the admin panel menu.
	 * If left unchanged (default 0), pages will appear in an
	 * undetermined order.
	 */
	protected int $position;
	
	/**
	 * The livewire component class
	 */
	protected string|null $view;
	
	/**
	 * The data to be passed to the view
	 */
	protected array $viewData;
	
	public function __construct(
		string $name,
		?string $uid = null,
		?string $icon = null,
		?string $menuLabel = null,
		?int $position = null,
		?string $url = null,
		?string $view = null,
		array $viewData = []
	)
	{
		$this->name = $name;
		$this->uid = $uid ?: Str::slug($name);
		$this->icon = $icon ?: 'heroicon:hashtag';
		$this->menuLabel = $menuLabel ?: $name;
		$this->url = Str::kebab($url ?: $this->uid);
		$this->position = $position ?: 0;
		$this->view = $view;
		$this->viewData = $viewData;
	}
	
	//--- Public methods ----------------------------------------------------------------------------------------------
	
	/**
	 * Return a page unique id. If none was given,
	 * the slug of the page name is used.
	 */
	public function getUid(): string
	{
		return $this->uid;
	}
	
	/**
	 * Get the admin page name
	 */
	public function getName(): string
	{
		return $this->name;
	}
	
	/**
	 * Get the menu label for the given page name. If no specific menu
	 * label was given, the page name is used. This allows us to
	 * use a shorter name in the menu, than the page title
	 */
	public function getMenuLabel(): string
	{
		return $this->menuLabel;
	}
	
	/**
	 * Get the icon of this page in the admin panel menu
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}
	
	public function getRawUrl(): string
	{
		return $this->url;
	}
	
	public function getUrl(): string
	{
		$prefix = trim(config('adminPanel.routePrefix', ''), '/');
		$pageUrl = trim($this->url, '/');
		
		return '/' . implode('/', array_filter([$prefix, $pageUrl]));
	}
	
	/**
	 * Get the position of this page in the admin panel menu.
	 */
	public function getPosition(): int
	{
		return $this->position;
	}
	
	/**
	 * Create a HeroIcon instance from a hero icon descriptor string.
	 * The descriptor must start with'hero' or 'heroicon', and
	 * must contain the name and optionally the format.
	 *
	 * e.g. heroicon:home:solid
	 */
	public function getHeroIcon(): string | null | HeroIcon
	{
		if (!$this->hasHeroIcon())
			return $this->icon;
		
		//get the icon parts and remove the first part (heroicon: / hero:)
		$iconParts = explode(':', $this->icon);
		array_shift($iconParts);
		
		//create a new icon instance, where the name is the first part, and the format is the second (format is optional)
		$icon = new HeroIcon($iconParts[0], $iconParts[1] ?? HeroIcon::FORMAT_OUTLINE);
		
		//if any classes are required, add the classes
		if (isset($iconParts[2])) {
			$icon->setClass($iconParts[2]);
		}
		
		$icon->removeSize()->useCurrentColor();
		
		return $icon;
	}
	
	/**
	 * Check whether the icon is a hero icon descriptor
	 */
	public function hasHeroIcon(): bool
	{
		return $this->icon
			&& is_string($this->icon)
			&& (stripos($this->icon, 'heroicon:') === 0 || stripos($this->icon, 'hero:') === 0);
	}
	
	public function getRawView(): string|null
	{
		return $this->view;
	}
	
	/**
	 * Get the livewire / blade view component string to be inserted in a blade
	 * file. If the view is not a livewire / blade component, the view
	 * attribute is returned. This allows using inline views.
	 */
	public function getView(): string|null
	{
		if ($this->viewIsLivewireComponent())
			return Livewire::getAlias($this->view) ?: $this->view::getName();
		
		if ($this->viewIsBladeComponent())
			return str_starts_with($this->view, 'components.')
				? (string) Str::of($this->view)->replaceFirst('components.', '')
				: $this->view;
		
		return $this->view;
	}
	
	public function viewIsLivewireComponent(): bool
	{
		return $this->view && is_subclass_of($this->view, LivewireComponent::class);
	}
	
	public function viewIsBladeComponent(): bool
	{
		return View::exists((string) Str::of($this->view)->start('components.'));
	}
	
	public function getViewData(): array
	{
		return $this->viewData;
	}
	
	/**
	 * Check whether this amin page is the
	 * active page in the admin panel
	 */
	public function isActive()
	{
		return Route::currentRouteName() === 'admin-panel'
			&& Route::current()->parameter('url') === $this->getRawUrl();
	}
}