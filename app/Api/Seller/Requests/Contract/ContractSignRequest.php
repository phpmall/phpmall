<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerContractSignRequest',
    required: [
        self::getSignType,
        self::getSignData,
    ],
    properties: [
        new OA\Property(property: self::getSignType, description: '签署类型:1电子签,2手动签', type: 'integer'),
        new OA\Property(property: self::getSignData, description: '签署数据', type: 'string'),
        new OA\Property(property: self::getRemark, description: '备注', type: 'string', nullable: true),
    ]
)]
class ContractSignRequest extends FormRequest
{
    const string getSignType = 'sign_type';

    const string getSignData = 'sign_data';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getSignType => 'required|integer|in:1,2',
            self::getSignData => 'required|string',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getSignType.'.required' => '请选择签署类型',
            self::getSignType.'.in' => '签署类型不正确',
            self::getSignData.'.required' => '请填写签署数据',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
