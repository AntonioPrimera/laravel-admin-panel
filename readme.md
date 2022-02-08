# Laravel Admin Panel

Although a lot of packages and solutions exist for creating an Admin Panel for Laravel Applications,
I find most of them too complicated or containing too much magic stuff.

The idea for this package is to have as quick as possible an admin panel set up for any Laravel
Application.

## Installation

Install the package in your Laravel 8+ application via composer:

`composer require antonioprimera\laravel-admin-panel`

If your project uses TailwindCss v2, you are all set if the css file containing the tailwind classes is
`public/css/app.css`.

**Note**: the admin panel requires the TailwindCss plugin `@tailwindcss/forms`!

### If TailwindCss is in a file, other than 'css/app.css'

If the TailwindCss classes are found in another file, you should configure
the asset path in config file "adminPanel.php" (either publish it from the package or create a new one):

`'projectTailwindCss' => 'css-folder/some-other-css-file.css',`

### If your project doesn't use TailwindCss v2

If your project doesn't use TailwindCss v2, you should set the projectTailwindCss config entry to boolean
false, in which case, the admin panel will use TailwindCss from CDN.

## How it all works

The principle behind is very simple: the package sets up a layout for the admin panel, and you can create
admin pages (manually or via an artisan command), which magically show up in your admin panel menu (routes
are auto-generated for each page).

Each page should have a different name, but for everything else, the package provides reasonable defaults.

#### Admin Page Livewire Components

Admin pages are currently Livewire components, each having a dedicated blade file. Admin Page Livewire
Components must inherit the abstract base component `AntonioPrimera\AdminPanel\View\AdminPage`.

#### Admin Page Blade Files

The blade files will only contain the actual view, no layout imports or references. Being a livewire
component blade file, the file must have a single root node. You can use any Livewire functionality
and TailwindCss v2 styling.

## Usage

By default, after installing the package and following the steps in the installation guide, you should
be able to access your admin panel in the browser at `http://your-local-project.url/admin-panel`.

### Creating a new AdminPanel Page

The easiest way to create a new AdminPanel Page is by using the provided artisan command:

`php artisan make:admin-page PageName`

If you want to group the admin pages by folder, you can provide a path instead of just the page name:

`php artisan make:admin-page PageFolder/PageName`

The command will print out the paths to the newly created livewire component and blade files.

## Configuration

The package offers a default configuration available under the 'adminPanel' key. If you want to override
the default values, you can publish the config file via `artisan vendor:publish` or create a new config
file of your own, named 'adminPanel.php' and override only the settings you need to change.

### Available configuration keys

#### layout

The default layout for the admin-panel is `admin-panel::livewire.layouts.admin-panel`. You should only
override this if you know what you are doing.

#### projectTailwindCss

This is the path to the css file in your project, relative to your public folder, containing the
TailwindCss classes. If your project does not use TailwindCss, you must override this setting and set it
to boolean `false`, so then the admin-panel will use a CDN version of TailwindCss. The default value
is `css/app.css`.

#### pages.folder

This setting contains the path to the folder containing the livewire admin panel component classes. The
folder is relative to the root folder of your project. The default folder is `app/Http/Livewire/AdminPanel`.

#### pages.namespace

This setting contains the root namespace of the livewire admin panel component classes. If you change
the folder containing your admin panel component classes, you probably have to also change this setting,
to match your psr4 namespace. The default namespace is `App\\Http\\Livewire\\AdminPanel`.

#### pages.viewPath and pages.bladePath

The combination of the *pages.viewPath* and *pages.bladePath* settings will determine the location of
the blade files corresponding to the livewire admin panel components. The *pages.bladePath* path is
relative to the *pages.viewPath* path. The defaults for these settings are: `resources/views` and
`livewire/admin-panel`, so that concatenated, they point to `resources/views/livewire/admin-panel`.

#### routePrefix

This is the route prefix for all admin panel pages and also the route to the admin panel dashboard (the
entry point to the admin panel). By default, this is `admin-panel`, so that your admin panel can be
accessed at `https://your.project.url/admin-panel`. If you want to better secure your admin panel, you can
use a radom route prefix, configurable in the corresponding config file or as an ENV variable:

`ADMIN_PANEL_ROUTE_PREFIX="/random/admin-panel/prefix"`

#### middleware

You can define the middleware to be applied to all admin-panel pages. By default, the following middleware
is configured to be applied `'middleware' => ['web', 'auth']`. For development purposes, you can remove the
`auth` middleware, so that you can access the admin panel without the need to register and log in. Don't
forget to add the auth middleware back, or some sort of protection, when you deploy your application.

## ToDo

- change the logo
- 