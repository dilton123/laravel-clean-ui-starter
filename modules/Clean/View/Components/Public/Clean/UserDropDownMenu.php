<?php

namespace Modules\Clean\View\Components\Public\Clean;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserDropDownMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $className)
    {
        //
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('global.components.user-drop-down-menu');
    }
}
