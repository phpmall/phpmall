<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Modules\Web\WebServiceProvider;
use Illuminate\Contracts\Support\Renderable;

abstract class BaseController extends Controller
{
    protected function view($template, array $vars = []): Renderable
    {
        return $this->display(WebServiceProvider::NS.'::'.$template, $vars);
    }
}
