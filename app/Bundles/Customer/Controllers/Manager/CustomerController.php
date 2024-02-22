<?php

declare(strict_types=1);

namespace App\Bundles\Customer\Controllers\Manager;

use App\Http\Controllers\Manager\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        return $this->success($request->path());
    }
}
