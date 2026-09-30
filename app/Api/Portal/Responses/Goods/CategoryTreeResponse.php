<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Goods;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PortalCategoryTreeResponse',
    title: '商品分类树节点',
    description: '无限级嵌套分类节点',
    properties: [
        new OA\Property(property: 'id', description: '分类ID', type: 'integer', example: 1),
        new OA\Property(property: 'parent_id', description: '父分类ID', type: 'integer', example: 0),
        new OA\Property(property: 'name', description: '分类名称', type: 'string', example: '手机数码'),
        new OA\Property(property: 'icon_url', description: '分类图标', type: 'string', nullable: true, example: '/icons/digital.png'),
        new OA\Property(property: 'level', description: '层级：1-一级，2-二级，3-三级', type: 'integer', example: 1),
        new OA\Property(property: 'children', description: '子分类列表', type: 'array', items: new OA\Items(ref: '#/components/schemas/PortalCategoryTreeResponse')),
    ]
)]
class CategoryTreeResponse
{
    //
}
