@props(['activeAdminPage' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') . ' - Admin Panel' }}</title>

	<!-- Fonts -->

	<!-- Styles -->
	@if(config('adminPanel.projectTailwindCss'))
		<link rel="stylesheet" href="{{ asset(config('adminPanel.projectTailwindCss')) }}">
	@else
		{{-- If the project does not use Tailwind, use this raw Tailwind version from CDN --}}
		<script src="https://cdn.tailwindcss.com"></script>
{{--		<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">--}}
	@endif

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>
	<script defer src="https://unpkg.com/alpinejs@3.8.1/dist/cdn.min.js"></script>

	<style>
		[x-cloak] { display: none !important; }
	</style>

	@livewireStyles
</head>

<body class="font-sans antialiased h-full">
	<div x-data="{showMobileMenu: false}">

		{{-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. --}}
		<div x-cloak x-show="showMobileMenu" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">

			{{-- Off-canvas menu overlay, show/hide based on off-canvas menu state. --}}
			<div x-show="showMobileMenu"
				 class="fixed inset-0 bg-gray-600 bg-opacity-75"
				 x-transition:enter="transition-opacity ease-linear duration-300"
				 x-transition:enter-start="opacity-0"
				 x-transition:enter-end="opacity-100"
				 x-transition:leave="transition-opacity ease-linear duration-300"
				 x-transition:leave-start="opacity-100"
				 x-transition:leave-end="opacity-0"
				 aria-hidden="true"
			></div>

			{{-- Off-canvas menu, show/hide based on off-canvas menu state. --}}
			<div x-show="showMobileMenu"
				 class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-gray-800"
				 x-transition:enter="transition ease-in-out duration-300 transform"
				 x-transition:enter-start="-translate-x-full"
				 x-transition:enter-end="translate-x-0"
				 x-transition:leave="transition ease-in-out duration-300 transform"
				 x-transition:leave-start="translate-x-0"
				 x-transition:leave-end="-translate-x-full"
			>

				{{-- Close button, show/hide based on off-canvas menu state. --}}
				<div x-show="showMobileMenu"
					 class="absolute top-0 right-0 -mr-12 pt-2"
					 x-transition:enter="ease-in-out duration-300"
					 x-transition:enter-start="opacity-0"
					 x-transition:enter-end="opacity-100"
					 x-transition:leave="ease-in-out duration-300"
					 x-transition:leave-start="opacity-100"
					 x-transition:leave-end="opacity-0"
				>
					<button @click="showMobileMenu = false" type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
						<span class="sr-only">Close sidebar</span>
						{{-- Heroicon name: outline/x --}}
						<svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>

				<div class="flex-shrink-0 flex items-center px-4">
					<a class="text-2xl font-semibold text-white" href="{{ route('admin-panel-dashboard') }}">{{ 'Admin Panel' }}</a>
				</div>

				<div class="mt-5 flex-1 h-0 overflow-y-auto">
					<nav class="px-2 space-y-1">

						@foreach(\AntonioPrimera\AdminPanel\Facades\AdminPanel::getPages() as $adminPage)
							<a href="{{ $adminPage->getUrl() }}" class="{{ $adminPage->isActive() ? "bg-gray-900 text-white" : "text-gray-300 hover:bg-gray-700 hover:text-white" }} group flex items-center px-2 py-2 text-base font-medium rounded-md">
								@if($adminPage->hasHeroIcon())
									{!! $adminPage->getHeroIcon()->setClass('mr-4 flex-shrink-0 h-6 w-6 ' . ($adminPage->isActive() ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300'))->render() !!}
								@else
									{!! $adminPage->getIcon() !!}
								@endif
								{{ $adminPage->getMenuLabel() }}
							</a>
						@endforeach

					</nav>
				</div>

				{{-- Sidebar Profile & Logout --}}
				@auth
					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<div class="flex-shrink-0 flex bg-gray-700 p-4">
							<a href="#" class="flex-shrink-0 w-full group block" onclick="event.preventDefault();this.closest('form').submit();">
								<div class="flex items-center">
									{{-- HeroIcon: user --}}
									<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>

									<div class="ml-3">
										<p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
										<p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">{{ __('Log Out') }}</p>
									</div>
								</div>
							</a>
						</div>
					</form>
				@endauth

			</div>

			<div class="flex-shrink-0 w-14" aria-hidden="true">
				{{-- Dummy element to force sidebar to shrink to fit close icon --}}
			</div>
		</div>

		<!-- Static sidebar for desktop -->
		<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
			<!-- Sidebar component, swap this element with another sidebar if you like -->
			<div class="flex-1 flex flex-col min-h-0 bg-gray-800">
				<div class="flex items-center h-16 flex-shrink-0 px-4 bg-gray-900">
					<a class="text-2xl font-semibold text-white" href="{{ route('admin-panel-dashboard') }}">{{ 'Admin Panel' }}</a>
				</div>
				<div class="flex-1 flex flex-col overflow-y-auto">
					<nav class="flex-1 px-2 py-4 space-y-1">
						{{-- todo: make a component for this menu and maybe use it also for the mobile menu --}}
						@foreach(\AntonioPrimera\AdminPanel\Facades\AdminPanel::getPages() as $adminPage)
							<a href="{{ $adminPage->getUrl() }}" class="{{ $adminPage->isActive() ? "bg-gray-900 text-white" : "text-gray-300 hover:bg-gray-700 hover:text-white" }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
								@if($adminPage->hasHeroIcon())
									{!! $adminPage->getHeroIcon()->setClass('mr-3 flex-shrink-0 h-6 w-6 ' . ($adminPage->isActive() ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300'))->render() !!}
								@else
									{!! $adminPage->getIcon() !!}
								@endif
								{{ $adminPage->getMenuLabel() }}
							</a>
						@endforeach
					</nav>
				</div>

				{{-- Sidebar Profile & Logout --}}
				@auth
					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<div class="flex-shrink-0 flex bg-gray-700 p-4">
							<a href="#" class="flex-shrink-0 w-full group block" onclick="event.preventDefault();this.closest('form').submit();">
								<div class="flex items-center">
									{{-- HeroIcon: user --}}
									<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>

									<div class="ml-3">
										<p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
										<p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">{{ __('Log Out') }}</p>
									</div>
								</div>
							</a>
						</div>
					</form>
				@endauth
			</div>
		</div>


		<div class="md:pl-64 flex flex-col">
			{{-- Header: Mobile (Burger + Page name) / Desktop (Page name) --}}
			<div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow md:hidden">
				<button @click="showMobileMenu = true" type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
					<span class="sr-only">Open sidebar</span>
					<!-- Heroicon name: outline/menu-alt-2 -->
					<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
					</svg>
				</button>
				<div class="flex-1 px-4 flex justify-between">
					<div class="max-w-7xl px-4 sm:px-6 md:px-8 flex-1 flex items-center">
						<h1 class="text-2xl font-semibold text-gray-900">{{ $activeAdminPage ? $activeAdminPage->getName() : 'Dashboard' }}</h1>
					</div>
				</div>
			</div>

			<main class="flex-1">
				{{ $slot }}
			</main>
		</div>
	</div>

	@livewireScripts
</body>
</html>
