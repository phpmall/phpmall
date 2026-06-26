<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Upload\FileRequest;
use App\Api\Common\Requests\Upload\ImageRequest;
use App\Api\Common\Requests\Upload\OssPolicyRequest;
use App\Api\Common\Responses\Upload\FileResponse;
use App\Api\Common\Responses\Upload\ImageResponse;
use App\Api\Common\Responses\Upload\OssPolicyResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UploadController extends BaseController
{
    #[OA\Post(path: '/image', summary: '图片上传', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ImageRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ImageResponse::class))]
    public function image(ImageRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/file', summary: '文件上传', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FileResponse::class))]
    public function file(FileRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/oss-policy', summary: 'OSS上传策略', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OssPolicyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OssPolicyResponse::class))]
    public function ossPolicy(OssPolicyRequest $request): JsonResponse
    {
        return $this->success();
    }
}
