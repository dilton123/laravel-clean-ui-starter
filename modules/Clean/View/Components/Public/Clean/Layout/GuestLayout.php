<?php

namespace Modules\Clean\View\Components\Public\Clean\Layout;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.public');
    }
}
