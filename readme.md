# Laravel Admin Panel

Although a lot of packages and solutions exist for creating an Admin Panel for Laravel Applications,
I find most of them too complicated to set up and manage.

The idea for this package is to have as quick as possible an admin panel set up for any Laravel
Application.

## How it all works

The principle behind is very simple: the package sets up a layout for the admin panel, and you can create
admin pages, as Livewire or Blade components. Once you register each page in the config `adminPanel.pages`,
your pages will be visible in the admin panel.

---

## Installation

Install the package in your Laravel 8+ application via composer:

`composer require antonioprimera\laravel-admin-panel`

**Note:** This package is not yet tested with Laravel 9+, but in principle it should work.

If your project uses TailwindCss v2 / Tailwind v3, you are all set if the css file containing the tailwind
classes is `public/css/app.css`.

**Note**: the admin panel requires the TailwindCss plugin `@tailwindcss/forms`!

### If TailwindCss is in a file, other than 'css/app.css'

If the TailwindCss classes are found in another file, you should configure
the asset path in config file "adminPanel.php" (either publish it from the package or create a new one):

`'projectTailwindCss' => 'css-folder/some-other-css-file.css',`

### If your project doesn't use TailwindCss v2+

If your project doesn't use TailwindCss v2+, you should set the projectTailwindCss config entry to boolean
false. The admin panel will use TailwindCss 3 from CDN.

---

## Usage

By default, after installing the package and following the steps in the installation guide, you should
be able to access your admin panel in the browser at `http://your-local-project.url/admin-panel`.

---

## The Admin Panel Configuration

You can create your own `adminPanel.php` config file, or you can publish the package config via:

`php artisan vendor:publish --tag=admin-panel-config`

### adminPanel.pages

This config key will hold the list and definition of all admin pages in the admin panel.

Let's start with an example for a 'Site Settings' admin page:

```php
return [
    'pages' => [
        'site-settings' => [
            'name'      => 'Site Settings',                                                 //required
            'view'      => MyProject\Html\Livewire\SiteSettingsAdminPageComponent::class,   //required
            'icon'      => 'heroicon:cog',                                                  //recommended
            'menuLabel' => 'Site Settings',                                                 //optional
            'position'  => 0,                                                               //optional
            'viewData'  => ['defaultTheme' => 'dark', 'otherData' => 'other stuff'],        //optional
        ],
        
        //... other pages
    ],
];
```

#### name (required)

This is a mandatory setting for each page. This is the human-readable name and title of the admin page. If the
'menuLabel' is not provided, this name will be used also as the label of this page in the admin panel menu. 

#### view (optional)

If you don't need some complex logic, you can just create a view and use it as an admin page. This view will be wrapped
in the admin panel layout.

You can use a livewire component (recommended), a blade component or an inline view.

###### View Option 1: Admin Page Livewire Components

The recommended approach is to create Livewire components for the Admin Pages. These are default Livewire
Components and don't have any restrictions. You can use TailwindCss v3 styling.

e.g.
```php
    'view' => MyProject\Html\Livewire\SomeAdminPageComponent::class
```


###### View Option 2: Admin Page Blade Files

The blade files should have a single root node (for future compatibility). You can use TailwindCss v3 styling.

e.g.
```php
    'view' => 'path.to.the-blade-file'
```


###### View Option 3: Inline html

Although not recommended, you can use inline HTML for the admin page view. This might be useful if you have
some components in a front-end JS Framework (like VueJS) and you don't want to create a blade file with a
single line of html.

e.g.
```php
    'view' => '<my-special-component :message="This might be a VueJS component"/>'
```

#### icon (recommended)

The Admin Panel package uses a simple package which enables simple HeroIcons integration. You can check out
this package at: [antonioprimera/laravel-heroicons](https://packagist.org/packages/antonioprimera/laravel-heroicons)

This allows you to define an icon from the [heroicons](https://heroicons.com/) page for each page, by simply
using the icon name, prefixed with 'heroicon:' or 'hero:'.

e.g. to use the 'cog' icon, you can just write
```php
    'icon' => 'heroicon:cog',   //or just 'hero:cog'
```

If you don't provide an icon, the AdminPanel will provide a default icon, but all pages without a defined icon
will have use the same icon in the menu.

#### menuLabel (optional)

By default, the Admin Page Name will be used as the page label in the menu. In some cases you might want to define a
different label (for example if the name is very long).

e.g.
```php
    'name'      => 'My Awesome Site Settings',
    'menuLabel' => 'Settings',
```

#### position (optional)

By default, admin pages show in a pseudo-random order in the admin panel menu, based on where they are defined. If
you want to set a specific order, you can define the page position as an integer.

#### viewData (optional)

By default, if the admin page view is self-sufficient, you can ignore this attribute. If you use a generic Livewire
or Blade component, which needs some data assigned, you can use this attribute to define an array of data.

e.g.
```php
    'viewData' => ['webComponentUid' => 'home-page'],
```

#### url (optional)

If you want to have an admin page point to a specific url, you can provide the full url here. If you don't provide
an url or a route, you must provide a view (Livewire / blade / inline), which the AdminPanel will associate with
its own route `admin-panel/{uid}`. In this case the uid will be used to differentiate the admin page.

e.g.
```php
    'url' => '/products/index',
```

#### route (optional)

If you use named routes, you can provide a route name, to determine the corresponding url. This is an alternative
to the `url` attribute (use one or the other, don't use both).

e.g.
```php
    'route' => 'products.index',
```


#### uid (optional)

By default, each page has a unique id, which is the array key of the admin page configuration. You can set a specific
uid for a page, to override a previously registered admin page with the same uid. Setting the Uid by yourself, might
produce unexpected results, so, only do this if you know what you're doing.

### adminPanel.layout

The `adminPanel.layout` config determines the layout blade file to be used for the admin panel. By default, the
layout is `admin-panel::layouts.default`.

### adminPanel.projectTailwindCss

The `adminPanel.projectTailwindCss` config determines the location of the css file containing the tailwind css classes,
relative to your public folder (by default 'css/app.css'). If your project doesn't use tailwind css or if you want a
clean and complete tailwind css file (from the tailwind cdn), you can set this entry to boolean false.

#### adminPanel.routePrefix

This is the route prefix for all admin panel pages and also the route to the admin panel dashboard (the
entry point to the admin panel). By default, this is `admin-panel`, so that your admin panel can be
accessed at `https://your.project.url/admin-panel`. If you want to better secure your admin panel, you can
use a random route prefix, configurable in `adminPanel.routePrefix` or as an ENV variable:

`ADMIN_PANEL_ROUTE_PREFIX="/random/admin-panel/prefix"`

#### adminPanel.middleware

You can define the middleware to be applied to all admin-panel pages. By default, the following middleware
is configured to be applied `'middleware' => ['web', 'auth']`.

You can set the env attribute `ADMIN_PANEL_MIDDLEWARE` to a comma separated list of middleware, without overriding
the default config. This allows you, for example, to just use the 'web' middleware (without 'auth') in your
development environment, so you don't have to create a user and log in every time to access your admin-panel.

e.g. `ADMIN_PANEL_MIDDLEWARE="web,some-special-middleware"`

## Using your own Controllers to add views to the Admin Panel

Starting with version 2.2 of the admin-panel, you can roll your own admin pages and easily add them to the
admin panel. This is especially useful if you want to associate more than one page with an admin panel menu item.

For example, if you have an '/admin-panel/products' page listing all your products, configured in your adminPanel
config file, you can add a product detail page to your admin panel, under the same admin page (the same menu item
appears as active): '/admin-panel/products/{product}'.

### The admin panel layout blade component

The admin-panel package exposes the currently configured admin panel layout as a blade component, aliased by
**x-admin-page** or **x-admin-panel-layout** (two aliases for the same layout).

The layout component accepts an optional parameter `uid`, which can be used to show one of the pages in the menu as
active. Behind the scenes, the view uses `AdminPanel::setCurrentPageUid(<active admin page uid>);` to set the given
uid as the currently active admin page uid in the admin panel.

e.g.

```php
//your adminPanel config - you must provide the proper url or route name for the admin page
return [
    //...
    'pages' => [
        'machines' => [
            'name' => 'Products'
            'icon' => 'hero:cog',
            'url' => '/products',   //or 'route' => 'products.index'
        ],
    ],
]
```

```php
//your Controller - no special treatment here
class MachineManager extends Controller
{
    public function index()
    {
        return view('machines.index', ['products' => Products::available()->get()]);
    }
}
```

```php
//your routes file (usually: routes/web.php) - no special treatment here
Route::get('/machines', [MachineController::class, 'index'])->name('machines.index');
```

```html
<!-- Your blade file (resources/views/machines/index.blade.php) -->
<!-- Just wrap everything in the x-admin-page layout and provide the active admin-page uid as an attribute -->
<x-admin-page uid="machines">
    ... your admin page content goes here ...
</x-admin-page>
```

## Package development & extending the Admin Page

The Admin Panel is intended to be easily integrated into existing laravel applications, but also easily extended and
used in other packages, where you want to publish admin pages to give your users access to some aspects of your package.

### The AdminPanel Facade

Through the AdminPanel Facade you have access to all settings of the admin panel and its admin pages. You can get
all admin pages, add new ones.

You can use this facade if you want to build your own admin panel layout, to read all admin pages and add menu items
for all admin pages.

### The AdminPage instances

The Admin Panel generates an `AntonioPrimera\AdminPanel\AdminPage` instance for each individual admin page. This class
exposes all properties of the admin page - their Url, Name, Uid, View, View Data etc.

### Generating admin pages in your packages

You can easily add admin pages for your package, without having to require this package, by simply adding a
page collector class in your Service Container and tagging it 'admin-pages'.

Here's an example for a HeroIcons package, which adds an admin page listing all available hero icons:

```php
//WebPageServiceProvider.php

public function register()
{
    //...
    $this->app->bind(HeroIconsAdminPageCollector::class);
    $this->app->tag(HeroIconsAdminPageCollector::class, 'admin-pages');
}
```

This page collector class can be any class, with a simple public method named 'resolve', which returns an array
of page configurations, exactly what you would write in your `adminPanel.pages` config.

Here's an example of a simple admin page collector for a package of HeroIcons:

```php
 
class HeroIconsAdminPageCollector
{

    public function resolve()
    {
        return [
            'hero-icons-admin-page' => [
                'name'      => 'Hero Icons',
                'icon'      => 'heroicon:shield-check',
                'view'      => 'hero-icons::admin-panel.icons-list',
                'viewData'  => ['icons' => HeroIcons::all()],
            ],
        ];
    }
}
```

Of course, you can even generate admin pages dynamically for models from the DB or any items in your package.