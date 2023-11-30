<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use Illuminate\View\View;

class IndexController extends BaseController
{
    public function index(): View
    {
        return view('portal::index');
    }
}
