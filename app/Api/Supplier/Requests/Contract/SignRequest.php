<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierContractSignRequest',
    required: [
        self::getSignType,
    ],
    properties: [
        new OA\Property(property: self::getSignType, description: '签署类型:1电子签,2手动签', type: 'integer'),
        new OA\Property(property: self::getSignImage, description: '签名图片URL', type: 'string', nullable: true),
        new OA\Property(property: self::getRemark, description: '签署备注', type: 'string', nullable: true),
    ]
)]
class SignRequest extends FormRequest
{
    const string getSignType = 'sign_type';

    const string getSignImage = 'sign_image';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getSignType => ['required', 'integer', 'in:1,2'],
            self::getSignImage => ['nullable', 'string', 'max:500'],
            self::getRemark => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getSignType.'.required' => '请选择签署类型',
            self::getSignType.'.in' => '签署类型值不正确',
            self::getSignImage.'.max' => '签名图片URL不能超过500个字符',
            self::getRemark.'.max' => '备注不能超过500个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
