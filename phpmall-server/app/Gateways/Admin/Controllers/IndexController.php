<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Controllers;

use Illuminate\Contracts\Support\Renderable;

class IndexController extends BaseController
{
    public function index(): Renderable
    {
        return $this->display('admin::index');
    }
}
