<x-admin-panel-layout>
	@if($viewType === \AntonioPrimera\AdminPanel\AdminPageManager::VIEW_TYPE_LIVEWIRE)
		@livewire($view, $viewData)
	@elseif($viewType === \AntonioPrimera\AdminPanel\AdminPageManager::VIEW_TYPE_BLADE)
		@include($view, $viewData)
	@elseif($viewType === \AntonioPrimera\AdminPanel\AdminPageManager::VIEW_TYPE_INLINE)
		{!! $view !!}
	@else
		<x-admin-panel::errors.missing-admin-page-view/>
	@endif
</x-admin-panel-layout>