<?php

declare(strict_types=1);

namespace App\Modules\Comment\API\Customer;

use App\API\Customer\Controllers\BaseController;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->success([]);
    }
}
