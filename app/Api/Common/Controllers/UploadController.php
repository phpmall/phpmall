<?php

declare(strict_types=1);

namespace App\Api\Common\Controllers;

use App\Api\Common\Requests\Upload\ConfirmRequest;
use App\Api\Common\Requests\Upload\FileRequest;
use App\Api\Common\Requests\Upload\ImageRequest;
use App\Api\Common\Requests\Upload\OssPolicyRequest;
use App\Api\Common\Responses\Upload\ConfirmResponse;
use App\Api\Common\Responses\Upload\FileResponse;
use App\Api\Common\Responses\Upload\ImageResponse;
use App\Api\Common\Responses\Upload\OssPolicyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

class UploadController extends BaseController
{
    #[OA\Post(path: '/image', summary: '图片上传', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ImageRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ImageResponse::class))]
    public function image(ImageRequest $request): JsonResponse
    {
        $file = $request->file(ImageRequest::getFile);

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString().($extension ? '.'.$extension : '');
        $path = 'images/'.now()->format('Y/m').'/'.$filename;

        $disk = Storage::disk('public');
        $disk->put($path, file_get_contents($file->getRealPath()));

        $response = new ImageResponse;
        $response->setUrl($disk->url($path));
        $response->setPath($path);
        $response->setName($file->getClientOriginalName());
        $response->setSize($file->getSize());
        $response->setMimeType($file->getMimeType());

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/file', summary: '文件上传', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FileResponse::class))]
    public function file(FileRequest $request): JsonResponse
    {
        $file = $request->file(FileRequest::getFile);

        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString().($extension ? '.'.$extension : '');
        $path = 'files/'.now()->format('Y/m').'/'.$filename;

        $disk = Storage::disk('public');
        $disk->put($path, file_get_contents($file->getRealPath()));

        $response = new FileResponse;
        $response->setUrl($disk->url($path));
        $response->setPath($path);
        $response->setName($file->getClientOriginalName());
        $response->setSize($file->getSize());
        $response->setMimeType($file->getMimeType());

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/oss-policy', summary: 'OSS上传策略', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OssPolicyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OssPolicyResponse::class))]
    public function ossPolicy(OssPolicyRequest $request): JsonResponse
    {
        $type = $request->input(OssPolicyRequest::getType);
        $filename = $request->input(OssPolicyRequest::getFilename);

        $ossConfig = config('filesystems.disks.oss');
        $accessKeyId = $ossConfig['access_key_id'] ?? '';
        $accessKeySecret = $ossConfig['access_key_secret'] ?? '';
        $bucket = $ossConfig['bucket'] ?? '';
        $endpoint = $ossConfig['endpoint'] ?? '';
        $cdnDomain = $ossConfig['cdn_domain'] ?? '';
        $callbackUrl = $ossConfig['callback_url'] ?? null;

        $host = $cdnDomain ?: $bucket.'.'.$endpoint;
        $uploadUrl = 'https://'.$host;

        $dir = $type.'s/'.now()->format('Y/m').'/';
        $extension = $filename ? pathinfo($filename, PATHINFO_EXTENSION) : '';
        $objectKey = $dir.Str::uuid()->toString().($extension ? '.'.$extension : '');

        $expire = time() + 3600;

        $policyArr = [
            'expiration' => gmdate('Y-m-d\TH:i:s.000Z', $expire),
            'conditions' => [
                ['bucket' => $bucket],
                ['content-length-range', 0, 50 * 1024 * 1024],
                ['starts-with', '$key', $dir],
            ],
        ];

        $policy = base64_encode(json_encode($policyArr));
        $signature = base64_encode(hash_hmac('sha1', $policy, $accessKeySecret, true));

        $response = new OssPolicyResponse;
        $response->setAccessKeyId($accessKeyId);
        $response->setPolicy($policy);
        $response->setSignature($signature);
        $response->setHost($host);
        $response->setExpire($expire);
        $response->setDir($objectKey);
        $response->setCallback($callbackUrl);

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/confirm', summary: '上传确认', security: [[]], tags: ['公共工具'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ConfirmRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ConfirmResponse::class))]
    public function confirm(ConfirmRequest $request): JsonResponse
    {
        $path = $request->input(ConfirmRequest::getPath);

        $ossConfig = config('filesystems.disks.oss');
        $cdnDomain = $ossConfig['cdn_domain'] ?? '';
        $bucket = $ossConfig['bucket'] ?? '';
        $endpoint = $ossConfig['endpoint'] ?? '';

        $host = $cdnDomain ?: $bucket.'.'.$endpoint;
        $url = 'https://'.$host.'/'.$path;

        $response = new ConfirmResponse;
        $response->setUrl($url);
        $response->setPath($path);

        return $this->success($response->toArray());
    }
}
