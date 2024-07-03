<?php

namespace Modules\Clean\View\Components\Public\Clean\Head;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Meta extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public string $excerpt,
        public ?string $coverImage,
        public string $url = '',
    ) {
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('public.components.head.meta');
    }
}
