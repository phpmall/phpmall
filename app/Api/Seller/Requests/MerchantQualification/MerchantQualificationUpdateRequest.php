<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\MerchantQualification;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerMerchantQualificationUpdateRequest',
    required: [
        self::getType,
        self::getName,
        self::getImage,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '资质类型:1营业执照,2经营许可证,3品牌授权,4其他', type: 'integer'),
        new OA\Property(property: self::getName, description: '资质名称', type: 'string', maxLength: 100),
        new OA\Property(property: self::getImage, description: '资质图片URL', type: 'string'),
        new OA\Property(property: self::getNumber, description: '资质编号', type: 'string', nullable: true),
        new OA\Property(property: self::getValidStart, description: '有效期开始', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: self::getValidEnd, description: '有效期结束', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: self::getIsPermanent, description: '是否永久有效:0否,1是', type: 'integer', nullable: true),
    ]
)]
class MerchantQualificationUpdateRequest extends FormRequest
{
    const string getType = 'type';

    const string getName = 'name';

    const string getImage = 'image';

    const string getNumber = 'number';

    const string getValidStart = 'valid_start';

    const string getValidEnd = 'valid_end';

    const string getIsPermanent = 'is_permanent';

    public function rules(): array
    {
        return [
            self::getType => ['required', 'integer', 'in:1,2,3,4'],
            self::getName => ['required', 'string', 'max:100'],
            self::getImage => ['required', 'string'],
            self::getNumber => ['nullable', 'string'],
            self::getValidStart => ['nullable', 'string', 'date'],
            self::getValidEnd => ['nullable', 'string', 'date'],
            self::getIsPermanent => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择资质类型',
            self::getType.'.in' => '资质类型不正确',
            self::getName.'.required' => '请填写资质名称',
            self::getName.'.max' => '资质名称不能超过100个字符',
            self::getImage.'.required' => '请上传资质图片',
            self::getValidStart.'.date' => '有效期开始日期格式不正确',
            self::getValidEnd.'.date' => '有效期结束日期格式不正确',
            self::getIsPermanent.'.in' => '是否永久有效值不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
