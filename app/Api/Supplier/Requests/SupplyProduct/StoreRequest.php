<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\SupplyProduct;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierSupplyProductStoreRequest',
    required: [
        self::getName,
        self::getCategoryId,
        self::getPrice,
        self::getUnit,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '商品名称', type: 'string'),
        new OA\Property(property: self::getCategoryId, description: '商品分类ID', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '商品描述', type: 'string', nullable: true),
        new OA\Property(property: self::getPrice, description: '供应单价(分)', type: 'integer'),
        new OA\Property(property: self::getUnit, description: '计量单位', type: 'string'),
        new OA\Property(property: self::getMinOrderQuantity, description: '最小起订量', type: 'integer', nullable: true),
        new OA\Property(property: self::getStock, description: '库存数量', type: 'integer', nullable: true),
        new OA\Property(property: self::getImages, description: '商品图片列表', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
        new OA\Property(property: self::getStatus, description: '状态:0下架,1上架', type: 'integer'),
    ]
)]
class StoreRequest extends FormRequest
{
    const string getName = 'name';

    const string getCategoryId = 'category_id';

    const string getDescription = 'description';

    const string getPrice = 'price';

    const string getUnit = 'unit';

    const string getMinOrderQuantity = 'min_order_quantity';

    const string getStock = 'stock';

    const string getImages = 'images';

    const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:200'],
            self::getCategoryId => ['required', 'integer', 'min:1'],
            self::getDescription => ['nullable', 'string', 'max:2000'],
            self::getPrice => ['required', 'integer', 'min:0'],
            self::getUnit => ['required', 'string', 'max:20'],
            self::getMinOrderQuantity => ['nullable', 'integer', 'min:1'],
            self::getStock => ['nullable', 'integer', 'min:0'],
            self::getImages => ['nullable', 'array'],
            self::getImages.'.*' => ['string', 'max:500'],
            self::getStatus => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写商品名称',
            self::getName.'.max' => '商品名称不能超过200个字符',
            self::getCategoryId.'.required' => '请选择商品分类',
            self::getPrice.'.required' => '请填写供应单价',
            self::getPrice.'.min' => '供应单价不能小于0',
            self::getUnit.'.required' => '请填写计量单位',
            self::getStatus.'.required' => '请选择状态',
            self::getStatus.'.in' => '状态值不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
