<?php

declare(strict_types=1);

namespace App\Portal\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    #[OA\Get(path: 'category', summary: '商品分类', tags: ['商品分类'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): Renderable
    {
        return $this->display('category');
    }
}
