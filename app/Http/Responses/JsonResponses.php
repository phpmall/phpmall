<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Juling\Foundation\Contracts\EnumMethodInterface;
use Throwable;

trait JsonResponses
{
    /**
     * 返回JSON数据
     */
    protected function json(array $data = [], array $headers = []): JsonResponse
    {
        return response()->json($data, 200, array_merge($headers, $this->getClientId()));
    }

    /**
     * 返回封装后的API数据到客户端
     */
    protected function success(array|string|null $data = null, int $code = 10000, array $headers = []): JsonResponse
    {
        return $this->json([
            'code' => $code,
            'message' => 'ok',
            'data' => $data,
        ], $headers);
    }

    /**
     * 返回异常数据到客户端
     */
    protected function error(EnumMethodInterface|Throwable|string $message = '', int $code = 50001, array $headers = []): JsonResponse
    {
        if ($message instanceof EnumMethodInterface) {
            $code = $message->getValue();
            $message = $message->getDescription();
        } elseif ($message instanceof Throwable) {
            $code = $message->getCode();
            $message = $message->getMessage();
        }

        return $this->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
        ], $headers);
    }

    /**
     * 返回请求客户端ID
     */
    protected function getClientId(string $key = 'X-Client-Id'): array
    {
        $clientId = request()->header($key, $this->createSessionId());

        return [$key => $clientId];
    }

    /**
     * 创建 Session ID
     */
    protected function createSessionId(): string
    {
        return bin2hex(pack('d', microtime(true)).pack('N', mt_rand()));
    }
}
