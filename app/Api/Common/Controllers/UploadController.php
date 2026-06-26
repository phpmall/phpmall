<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UploadController extends BaseController
{
    #[OA\Post(path: '/image', summary: '图片上传', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function image(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/file', summary: '文件上传', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function file(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/oss-policy', summary: 'OSS上传策略', security: [[]], tags: ['公共工具'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function ossPolicy(): JsonResponse
    {
        return $this->success();
    }
}
