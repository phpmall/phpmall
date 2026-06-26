<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierWarehouseStoreRequest',
    required: [
        self::getName,
        self::getCode,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '仓库名称', type: 'string'),
        new OA\Property(property: self::getCode, description: '仓库编码', type: 'string'),
        new OA\Property(property: self::getAddress, description: '仓库地址', type: 'string', nullable: true),
        new OA\Property(property: self::getContactName, description: '联系人', type: 'string', nullable: true),
        new OA\Property(property: self::getContactPhone, description: '联系电话', type: 'string', nullable: true),
        new OA\Property(property: self::getStatus, description: '状态:0禁用,1启用', type: 'integer'),
        new OA\Property(property: self::getIsDefault, description: '是否默认仓库:0否,1是', type: 'integer'),
    ]
)]
class StoreRequest extends FormRequest
{
    const string getName = 'name';

    const string getCode = 'code';

    const string getAddress = 'address';

    const string getContactName = 'contact_name';

    const string getContactPhone = 'contact_phone';

    const string getStatus = 'status';

    const string getIsDefault = 'is_default';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:100'],
            self::getCode => ['required', 'string', 'max:50'],
            self::getAddress => ['nullable', 'string', 'max:500'],
            self::getContactName => ['nullable', 'string', 'max:50'],
            self::getContactPhone => ['nullable', 'string', 'max:20'],
            self::getStatus => ['required', 'integer', 'in:0,1'],
            self::getIsDefault => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写仓库名称',
            self::getName.'.max' => '仓库名称不能超过100个字符',
            self::getCode.'.required' => '请填写仓库编码',
            self::getCode.'.max' => '仓库编码不能超过50个字符',
            self::getAddress.'.max' => '地址不能超过500个字符',
            self::getContactName.'.max' => '联系人不能超过50个字符',
            self::getContactPhone.'.max' => '联系电话不能超过20个字符',
            self::getStatus.'.required' => '请选择状态',
            self::getStatus.'.in' => '状态值不正确',
            self::getIsDefault.'.required' => '请选择是否默认仓库',
            self::getIsDefault.'.in' => '是否默认仓库值不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
