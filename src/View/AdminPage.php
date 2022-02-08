<?php

namespace AntonioPrimera\AdminPanel\View;

use AntonioPrimera\AdminPanel\AdminPageManager;
use AntonioPrimera\HeroIcons\HeroIcon;
use Illuminate\Support\Str;
use Livewire\Component;

abstract class AdminPage extends Component
{
	protected static $adminMenuLabel = null;
	protected static $adminPageName  = "CHANGE ME!";
	
	/**
	 * In order to override an existing admin page, set this page to have the same
	 * $adminPageUid as the page you want to override. This is used as the key
	 * for the page. If null, it defaults to the slug of $adminPageName
	 *
	 * @var null | string
	 */
	protected static $adminPageUid   = null;
	
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
	 *
	 * @var null
	 */
	protected static $adminMenuIcon = null;
	
	/**
	 * Override this if you want to have a specific url for this page. If null,
	 * the page name slug (page uid) will be used as the url. If
	 * complexUrls are used, the value will be hashed.
	 *
	 * @var null
	 */
	protected static $adminPageUrl = null;
	
	/**
	 * The order in which it should appear in the admin panel menu.
	 * If left unchanged (default 0), pages will appear in an
	 * undetermined order.
	 *
	 * @var int
	 */
	protected static $adminPagePosition = 0;
	
	/**
	 * Use this method in your component render method, instead of the view function.
	 * This function wraps the view helper, but sets the admin panel layout and
	 * sets some layout data, for the admin panel to function correctly.
	 *
	 * e.g. instead of
	 * 		return view('my-admin-page')
	 * 	use
	 * 		return $this->adminPageView('my-admin-page')
	 *
	 * @param ...$arguments
	 *
	 * @return mixed
	 */
	protected function adminPageView(...$arguments)
	{
		//dd(view(...$arguments), $arguments);
		
		/** @noinspection PhpUndefinedMethodInspection */
		return view(...$arguments)
			->layout(config('adminPanel.layout'))
			->withLayoutData([
				'adminPageName' 	 => static::getAdminPageName(),
				'activeAdminPageUid' => static::getAdminPageUid(),
				'adminMenu'			 => static::getAdminMenu(),
			]);
	}
	
	protected function getAdminMenu()
	{
		return AdminPageManager::getAdminComponentClasses()
			->map(function($pageClass, $pageUid) {
				return [
					'uid'      => $pageUid,
					'label'    => $pageClass::getAdminMenuLabel(),
					'icon'	   => $pageClass::getAdminMenuIcon(),
					'url'	   => $pageClass::getAdminPageUrl(),
					'position' => $pageClass::getAdminPagePosition(),
				];
			})
			->sortBy('position');
		
	}
	
	//--- Public static methods ---------------------------------------------------------------------------------------
	
	/**
	 * Return a page unique id, based on its name. If a page in the app has the same slug
	 * as a page in the package, the latter overrides the package admin page. The
	 * slug of the name must be unique across pages.
	 *
	 * @return string
	 */
	public static function getAdminPageUid(): string
	{
		return static::$adminPageUid ?: Str::slug(static::$adminPageName);
	}
	
	/**
	 * Get the admin page name - don't forget to override the
	 * protected static $adminPageName attribute
	 *
	 * @return string
	 */
	public static function getAdminPageName(): string
	{
		return static::$adminPageName;
	}
	
	/**
	 * Get the menu label for the given page name. If no specific menu label was given
	 * in the protected static $adminMenuLabel attribute, the page name is used.
	 * This allows us to use a shorter name in the menu, than the title.
	 *
	 * @return string
	 */
	public static function getAdminMenuLabel(): string
	{
		return static::$adminMenuLabel ?: static::$adminPageName;
	}
	
	/**
	 * Get the icon of this page in the admin panel menu
	 */
	public static function getAdminMenuIcon(): HeroIcon | null
	{
		return static::getHeroIcon(static::$adminMenuIcon);
	}
	
	public static function getAdminPageUrl(): string
	{
		$prefix = trim(config('adminPanel.routePrefix', ''), '/');
		$pageUrl = trim(static::$adminPageUrl ?: static::getAdminPageUid(), '/');
		
		return '/' . implode('/', array_filter([$prefix, $pageUrl]));
	}
	
	/**
	 * Get the position of this page in the admin panel menu.
	 *
	 * @return int
	 */
	public static function getAdminPagePosition(): int
	{
		return static::$adminPagePosition;
	}
	
	protected static function isHeroIconDescriptor(?string $icon)
	{
		return $icon
			&& is_string($icon)
			&& (stripos($icon, 'heroicon:') === 0 || stripos($icon, 'hero:') === 0);
	}
	
	//--- Protected static helpers ------------------------------------------------------------------------------------
	
	/**
	 * Create a HeroIcon instance from a hero icon descriptor string.
	 * The descriptor must start with'hero' or 'heroicon', and
	 * must contain the name and optionally the format.
	 *
	 * e.g. heroicon:home:solid
	 */
	protected static function getHeroIcon(?string $iconDescriptor): string | null | HeroIcon
	{
		if (!static::isHeroIconDescriptor($iconDescriptor))
			return $iconDescriptor;
		
		//get the icon parts and remove the first part (heroicon: / hero:)
		$iconParts = explode(':', $iconDescriptor);
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
}