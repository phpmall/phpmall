<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Merchant;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerMerchantUpdateRequest',
    required: [
        self::getName,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '商家名称', type: 'string', maxLength: 100),
        new OA\Property(property: self::getLogo, description: '商家Logo', type: 'string', nullable: true),
        new OA\Property(property: self::getDescription, description: '商家描述', type: 'string', nullable: true),
        new OA\Property(property: self::getContactName, description: '联系人姓名', type: 'string', nullable: true),
        new OA\Property(property: self::getContactPhone, description: '联系人电话', type: 'string', nullable: true),
        new OA\Property(property: self::getContactEmail, description: '联系人邮箱', type: 'string', nullable: true),
        new OA\Property(property: self::getProvince, description: '省份', type: 'string', nullable: true),
        new OA\Property(property: self::getCity, description: '城市', type: 'string', nullable: true),
        new OA\Property(property: self::getDistrict, description: '区县', type: 'string', nullable: true),
        new OA\Property(property: self::getAddress, description: '详细地址', type: 'string', nullable: true),
        new OA\Property(property: self::getBusinessLicense, description: '营业执照编号', type: 'string', nullable: true),
        new OA\Property(property: self::getBusinessLicenseImage, description: '营业执照图片', type: 'string', nullable: true),
    ]
)]
class MerchantUpdateRequest extends FormRequest
{
    const string getName = 'name';

    const string getLogo = 'logo';

    const string getDescription = 'description';

    const string getContactName = 'contact_name';

    const string getContactPhone = 'contact_phone';

    const string getContactEmail = 'contact_email';

    const string getProvince = 'province';

    const string getCity = 'city';

    const string getDistrict = 'district';

    const string getAddress = 'address';

    const string getBusinessLicense = 'business_license';

    const string getBusinessLicenseImage = 'business_license_image';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:100'],
            self::getLogo => ['nullable', 'string'],
            self::getDescription => ['nullable', 'string'],
            self::getContactName => ['nullable', 'string', 'max:50'],
            self::getContactPhone => ['nullable', 'string'],
            self::getContactEmail => ['nullable', 'string', 'email'],
            self::getProvince => ['nullable', 'string'],
            self::getCity => ['nullable', 'string'],
            self::getDistrict => ['nullable', 'string'],
            self::getAddress => ['nullable', 'string'],
            self::getBusinessLicense => ['nullable', 'string'],
            self::getBusinessLicenseImage => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写商家名称',
            self::getName.'.max' => '商家名称不能超过100个字符',
            self::getContactName.'.max' => '联系人姓名不能超过50个字符',
            self::getContactEmail.'.email' => '邮箱格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
