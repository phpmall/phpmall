<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '首页')]
    public function index(Request $request): Renderable
    {
        return $this->view('index');
    }
}
