<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Region\IndexRequest;
use App\Api\Portal\Responses\Region\RegionListResponse;
use App\Modules\System\Services\SystemRegionService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RegionController extends BaseController
{
    #[OA\Get(path: '/regions', summary: '省市区列表', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'parent_code', description: '父级地区编码，顶级传 0', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RegionListResponse::class))]
    public function index(IndexRequest $request, SystemRegionService $service): JsonResponse
    {
        $parentCode = $request->input(IndexRequest::getParentCode, '0');

        $regions = $service->getList(
            ['parent_code' => $parentCode],
            'id',
            'asc'
        );

        $items = array_map(fn (array $region): array => [
            'id' => (int) $region['id'],
            'parentCode' => $region['parent_code'],
            'name' => $region['name'],
            'code' => $region['code'],
            'level' => (int) $region['level'],
            'zipCode' => $region['zip_code'],
            'hasChildren' => (bool) $region['has_children'],
        ], $regions);

        return $this->success(['items' => $items]);
    }
}
