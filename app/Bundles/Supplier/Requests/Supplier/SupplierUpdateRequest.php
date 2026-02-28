<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierUpdateRequest',
    required: [
        self::getSuppliersId,
        self::getSuppliersName,
        self::getSuppliersDesc,
        self::getIsCheck,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getSuppliersId, description: '', type: 'integer'),
        new OA\Property(property: self::getSuppliersName, description: '供应商名称', type: 'string'),
        new OA\Property(property: self::getSuppliersDesc, description: '供应商描述', type: 'string'),
        new OA\Property(property: self::getIsCheck, description: '是否审核', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class SupplierUpdateRequest extends FormRequest
{
    const string getSuppliersId = 'suppliersId';

    const string getSuppliersName = 'suppliersName';

    const string getSuppliersDesc = 'suppliersDesc';

    const string getIsCheck = 'isCheck';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getSuppliersId => 'required',
            self::getSuppliersName => 'required',
            self::getSuppliersDesc => 'required',
            self::getIsCheck => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getSuppliersId.'.required' => '请设置',
            self::getSuppliersName.'.required' => '请设置供应商名称',
            self::getSuppliersDesc.'.required' => '请设置供应商描述',
            self::getIsCheck.'.required' => '请设置是否审核',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
