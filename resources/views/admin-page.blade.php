<x-dynamic-component :component="$layout" :active-admin-page="$adminPage">
	@if($adminPage->viewIsLivewireComponent())
		@livewire($adminPage->getView(), $adminPage->getViewData())
	@elseif($adminPage->viewIsBladeComponent())
		<x-dynamic-component :component="$adminPage->getView()"/>
	@elseif($adminPage->getView())
		{!! $adminPage->getView() !!}
	@else
		<x-admin-panel::errors.missing-admin-page-view/>
	@endif
</x-dynamic-component>