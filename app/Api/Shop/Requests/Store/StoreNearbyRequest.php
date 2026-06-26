<?php

declare(strict_types=1);

namespace App\Api\Shop\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopStoreNearbyRequest',
    required: [
        self::getLatitude,
        self::getLongitude,
    ],
    properties: [
        new OA\Property(property: self::getLatitude, description: '纬度', type: 'number', format: 'float'),
        new OA\Property(property: self::getLongitude, description: '经度', type: 'number', format: 'float'),
        new OA\Property(property: self::getRadius, description: '搜索半径(米)', type: 'integer', example: 5000),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class StoreNearbyRequest extends FormRequest
{
    const string getLatitude = 'latitude';

    const string getLongitude = 'longitude';

    const string getRadius = 'radius';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function rules(): array
    {
        return [
            self::getLatitude => 'required|numeric|between:-90,90',
            self::getLongitude => 'required|numeric|between:-180,180',
            self::getRadius => 'sometimes|integer|min:100|max:50000',
            self::getPage => 'sometimes|integer|min:1',
            self::getPerPage => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLatitude.'.required' => '请填写纬度',
            self::getLatitude.'.numeric' => '纬度必须是数字',
            self::getLatitude.'.between' => '纬度范围必须在-90到90之间',
            self::getLongitude.'.required' => '请填写经度',
            self::getLongitude.'.numeric' => '经度必须是数字',
            self::getLongitude.'.between' => '经度范围必须在-180到180之间',
            self::getRadius.'.integer' => '搜索半径必须是整数',
            self::getRadius.'.min' => '搜索半径不能小于100米',
            self::getRadius.'.max' => '搜索半径不能超过50000米',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
