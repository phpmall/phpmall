<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Portal\Providers\PortalServiceProvider;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view(PortalServiceProvider::MODULE.'::layouts.app');
    }
}
