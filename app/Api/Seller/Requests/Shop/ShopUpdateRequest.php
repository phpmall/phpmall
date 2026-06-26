<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShopUpdateRequest',
    required: [
        self::getName,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '店铺名称', type: 'string', maxLength: 50),
        new OA\Property(property: self::getLogo, description: '店铺Logo', type: 'string', nullable: true),
        new OA\Property(property: self::getBanner, description: '店铺Banner', type: 'string', nullable: true),
        new OA\Property(property: self::getDescription, description: '店铺描述', type: 'string', nullable: true),
        new OA\Property(property: self::getNotice, description: '店铺公告', type: 'string', nullable: true),
        new OA\Property(property: self::getContactPhone, description: '联系电话', type: 'string', nullable: true),
        new OA\Property(property: self::getContactEmail, description: '联系邮箱', type: 'string', nullable: true),
        new OA\Property(property: self::getProvince, description: '省份', type: 'string', nullable: true),
        new OA\Property(property: self::getCity, description: '城市', type: 'string', nullable: true),
        new OA\Property(property: self::getDistrict, description: '区县', type: 'string', nullable: true),
        new OA\Property(property: self::getAddress, description: '详细地址', type: 'string', nullable: true),
    ]
)]
class ShopUpdateRequest extends FormRequest
{
    const string getName = 'name';

    const string getLogo = 'logo';

    const string getBanner = 'banner';

    const string getDescription = 'description';

    const string getNotice = 'notice';

    const string getContactPhone = 'contact_phone';

    const string getContactEmail = 'contact_email';

    const string getProvince = 'province';

    const string getCity = 'city';

    const string getDistrict = 'district';

    const string getAddress = 'address';

    public function rules(): array
    {
        return [
            self::getName => ['required', 'string', 'max:50'],
            self::getLogo => ['nullable', 'string'],
            self::getBanner => ['nullable', 'string'],
            self::getDescription => ['nullable', 'string'],
            self::getNotice => ['nullable', 'string'],
            self::getContactPhone => ['nullable', 'string'],
            self::getContactEmail => ['nullable', 'string', 'email'],
            self::getProvince => ['nullable', 'string'],
            self::getCity => ['nullable', 'string'],
            self::getDistrict => ['nullable', 'string'],
            self::getAddress => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请填写店铺名称',
            self::getName.'.max' => '店铺名称不能超过50个字符',
            self::getContactEmail.'.email' => '邮箱格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
