<?php

declare(strict_types=1);

namespace App\Bundles\Customer\Controllers\Admin;

use App\Gateways\Admin\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        return $this->success($request->path());
    }
}
