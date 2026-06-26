<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CatalogController extends BaseController
{
    #[OA\Get(path: '/catalog', summary: '类目')]
    public function index(Request $request): Renderable
    {
        return $this->view('catalog');
    }
}
