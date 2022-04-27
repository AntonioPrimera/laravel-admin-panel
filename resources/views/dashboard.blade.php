<x-admin-panel-layout>
	@if($adminPages->isEmpty())
		<x-admin-panel::errors.generic title="No admin pages" description="Your admin panel is empty and doesn't contain any admin pages."/>
	@else
		<div class="grid grid-cols-3 gap-6">
			@foreach($adminPages as $adminPage)
				<a class="rounded-md bg-white border border-gray-100 shadow h-32 flex items-center justify-center text-xl font-bold text-gray-700 hover:text-black hover:border-gray-300" href="{{ $adminPage->getUrl() }}">
					<span>{{ $adminPage->getMenuLabel() }}</span>
				</a>
			@endforeach
		</div>
	@endif
</x-admin-panel-layout>