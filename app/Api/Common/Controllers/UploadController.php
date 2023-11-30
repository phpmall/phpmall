<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Exceptions\CustomException;
use App\Api\Common\Requests\UploadRequest;
use App\Api\Common\Responses\UploadResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;
use Throwable;

class UploadController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    #[OA\Post(path: '/common/upload', summary: '附件上传接口', security: [['bearerAuth' => []]], tags: ['素材'])]
    #[OA\RequestBody(
        required: true,
        content: [
            new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: UploadRequest::class)
            ),
        ]
    )]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UploadResponse::class))]
    public function index(UploadRequest $request): JsonResponse
    {
        $requestData = $request->validated();

        try {
            // 获取表单上传文件
            /** @var UploadedFile $file */
            $file = $requestData->file;
            $fileName = implode('/', ['attachment', $file->hashName().'.'.$file->extension()]);

            // 转存到OSS
            Storage::putFile($fileName, $file->getPathname());

            $uploadResponse = new UploadResponse();
            $uploadResponse->setUrl(Storage::url($fileName));

            return $this->success($uploadResponse->toArray());
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error('附件上传错误');
        }
    }
}
