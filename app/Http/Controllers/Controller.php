<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * 模板变量
     */
    protected array $vars = [];

    /**
     * 响应内容自动协商
     */
    protected function response($template, array $vars = []): JsonResponse|Renderable
    {
        if (request()->expectsJson()) {
            return $this->success(array_merge($this->vars, $vars));
        } else {
            return $this->display($template, $vars);
        }
    }

    /**
     * 变量赋值
     */
    protected function assign($name, $value): void
    {
        $this->vars = array_merge($this->vars, [$name => $value]);
    }

    /**
     * 获取内容
     */
    protected function fetch($template, array $vars = []): string
    {
        return $this->display($template, $vars)->render();
    }

    /**
     * 视图渲染
     */
    protected function display($template, array $vars = []): Renderable
    {
        if (! empty($vars)) {
            $this->vars = array_merge($this->vars, $vars);
        }

        return view($template, $this->vars);
    }

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
    protected function success(array $data = [], array $headers = []): JsonResponse
    {
        return $this->json([
            'code' => 0,
            'message' => 'ok',
            'data' => $data,
        ], $headers);
    }

    /**
     * 返回异常数据到客户端
     */
    protected function error(string $message = '', int $code = 40001, array $headers = []): JsonResponse
    {
        return $this->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
        ], $headers);
    }

    /**
     * 返回请求客户端ID
     */
    protected function getClientId(string $clientName = 'X-Client-Id'): array
    {
        $clientValue = request()->header($clientName, $this->createSessionId());

        return [$clientName => $clientValue];
    }

    /**
     * 创建 Session ID
     */
    protected function createSessionId(): string
    {
        return bin2hex(pack('d', microtime(true)).pack('N', mt_rand()));
    }
}
