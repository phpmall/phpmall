<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use App\Bundles\Foundation\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;

abstract class BaseController extends Controller
{
    /**
     * 视图渲染
     */
    protected function display($template, array $vars = []): Renderable
    {
        return parent::display('portal::'.$template, $vars);
    }
}
