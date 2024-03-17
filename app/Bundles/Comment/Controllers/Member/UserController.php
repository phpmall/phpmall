<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Controllers\Member;

use App\API\User\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
