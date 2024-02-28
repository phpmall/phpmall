<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Controllers\Manager;

use App\Http\Controllers\Manager\BaseController;
use Illuminate\Http\JsonResponse;

class IndexController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
