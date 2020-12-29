<?php

declare(strict_types=1);

namespace app\support;

use think\helper\Str;
use think\Response;

/**
 * Trait JsonResponse
 * @package app\support
 */
trait JsonResponse
{
    /**
     * @var int
     */
    protected $errorCode = 0;

    /**
     * @return int
     */
    protected function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     * @return $this
     */
    protected function setErrorCode(int $errorCode): JsonResponse
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * 返回封装后的API数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param array $headers 发送的Header信息
     * @return Response
     */
    protected function succeed($data, array $headers = []): Response
    {
        return $this->response([
            'status' => 'success',
            'data' => $data,
        ], $headers);
    }

    /**
     * 返回异常数据到客户端
     * @param $message
     * @return Response
     */
    protected function failed($message): Response
    {
        return $this->response([
            'status' => 'failed',
            'errors' => [
                'code' => $this->getErrorCode(),
                'message' => $message,
            ],
        ]);
    }

    /**
     * 返回 Json 数据格式
     * @param $data
     * @param array $header
     * @param string $name
     * @return Response
     */
    protected function response($data, $header = [], $name = 'X-Client-Id'): Response
    {
        $clientId = request()->header($name);

        if (empty($clientId)) {
            $clientId = md5(session_create_id() . Str::random());
        }

        return json($data, 200, array_merge($header, [$name => $clientId]));
    }
}
