<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    /**
     * 统一成功响应
     */
    protected function success(array|string|null $data = null, array $headers = []): JsonResponse
    {
        return parent::success($data, $headers);
    }

    /**
     * 统一失败响应
     */
    protected function fail(string $message = 'error', int $code = -1, mixed $data = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
