<?php

namespace App\View\Components\Admin\Clean;

use Illuminate\View\Component;

class Sidebar extends Component
{
    protected array $userPermissions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $userPermissions)
    {
        $this->userPermissions = $userPermissions;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.sidebar');
    }
}
