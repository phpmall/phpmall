<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Kyc;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'KycSubmitRequest',
    required: [
        self::getRealName,
        self::getIdNumber,
        self::getIdFrontImage,
        self::getIdBackImage,
    ],
    properties: [
        new OA\Property(property: self::getRealName, description: '真实姓名', type: 'string'),
        new OA\Property(property: self::getIdNumber, description: '身份证号', type: 'string'),
        new OA\Property(property: self::getIdFrontImage, description: '身份证正面照URL', type: 'string', format: 'uri'),
        new OA\Property(property: self::getIdBackImage, description: '身份证背面照URL', type: 'string', format: 'uri'),
        new OA\Property(property: self::getFaceImage, description: '人脸识别照片URL', type: 'string', format: 'uri', nullable: true),
    ]
)]
class KycSubmitRequest extends FormRequest
{
    const string getRealName = 'real_name';

    const string getIdNumber = 'id_number';

    const string getIdFrontImage = 'id_front_image';

    const string getIdBackImage = 'id_back_image';

    const string getFaceImage = 'face_image';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getRealName => ['required', 'string', 'max:50'],
            self::getIdNumber => ['required', 'string', 'regex:/^\d{17}[\dXx]$/'],
            self::getIdFrontImage => ['required', 'string', 'url', 'max:500'],
            self::getIdBackImage => ['required', 'string', 'url', 'max:500'],
            self::getFaceImage => ['nullable', 'string', 'url', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getRealName.'.required' => '请填写真实姓名',
            self::getIdNumber.'.required' => '请填写身份证号',
            self::getIdNumber.'.regex' => '身份证号格式不正确',
            self::getIdFrontImage.'.required' => '请上传身份证正面照',
            self::getIdBackImage.'.required' => '请上传身份证背面照',
        ];
    }
}
