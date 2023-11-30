<?php

declare(strict_types=1);

namespace App\Gateways\Common\Controllers;

use App\Foundation\Controllers\Controller;
use App\Gateways\Common\Requests\RegionRequest;
use App\Gateways\Common\Responses\RegionResponse;
use App\Services\RegionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class RegionController extends Controller
{
    #[OA\Get(path: '/common/region', summary: '查询地区列表', tags: ['地区'])]
    #[OA\QueryParameter(name: 'id', description: '上级地区ID，默认值为0显示省份数据', required: true, example: 0)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RegionResponse::class))]
    public function index(RegionRequest $request): JsonResponse
    {
        $requestData = $request->validated();

        try {
            $cacheKey = 'system_region_'.$requestData['id'];
            $data = Cache::rememberForever($cacheKey, function () use ($requestData) {
                $regionService = new RegionService();

                return $regionService->getList([
                    ['parent_id', '=', $requestData['id']],
                ]);
            });

            foreach ($data as $key => $region) {
                $regionResponse = new RegionResponse();
                $regionResponse->setId($region['id']);
                $regionResponse->setName($region['name']);
                $regionResponse->setFirstLetter($region['first_letter']);
                $data[$key] = $regionResponse->toArray();
            }

            return $this->success($data);
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error('获取地区信息错误');
        }
    }
}
