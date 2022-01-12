<?php

namespace AntonioPrimera\AdminPanel;

class AdminPanelViewMacros
{
	
	public function withLayoutData()
	{
		return function ($data = []) {
			$this->livewireLayout['params'] = $data;
			
			return $this;
		};
	}
	
}