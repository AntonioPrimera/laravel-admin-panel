<?php

namespace AntonioPrimera\AdminPanel\View\Components;

use Illuminate\View\Component;

class Layout extends Component
{
	
    public function render()
	{
        return view('admin-panel::layouts.admin-panel');
    }
    
}
