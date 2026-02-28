<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAddress;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAddressCreateRequest',
    required: [
        self::getAddressId,
        self::getAddressName,
        self::getUserId,
        self::getConsignee,
        self::getEmail,
        self::getCountry,
        self::getProvince,
        self::getCity,
        self::getDistrict,
        self::getAddress,
        self::getZipcode,
        self::getTel,
        self::getMobile,
        self::getSignBuilding,
        self::getBestTime,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getAddressId, description: '', type: 'integer'),
        new OA\Property(property: self::getAddressName, description: '地址名称', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getConsignee, description: '收货人', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getCountry, description: '国家', type: 'integer'),
        new OA\Property(property: self::getProvince, description: '省份', type: 'integer'),
        new OA\Property(property: self::getCity, description: '城市', type: 'integer'),
        new OA\Property(property: self::getDistrict, description: '区县', type: 'integer'),
        new OA\Property(property: self::getAddress, description: '详细地址', type: 'string'),
        new OA\Property(property: self::getZipcode, description: '邮编', type: 'string'),
        new OA\Property(property: self::getTel, description: '电话', type: 'string'),
        new OA\Property(property: self::getMobile, description: '手机', type: 'string'),
        new OA\Property(property: self::getSignBuilding, description: '标志建筑', type: 'string'),
        new OA\Property(property: self::getBestTime, description: '最佳送货时间', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserAddressCreateRequest extends FormRequest
{
    const string getAddressId = 'addressId';

    const string getAddressName = 'addressName';

    const string getUserId = 'userId';

    const string getConsignee = 'consignee';

    const string getEmail = 'email';

    const string getCountry = 'country';

    const string getProvince = 'province';

    const string getCity = 'city';

    const string getDistrict = 'district';

    const string getAddress = 'address';

    const string getZipcode = 'zipcode';

    const string getTel = 'tel';

    const string getMobile = 'mobile';

    const string getSignBuilding = 'signBuilding';

    const string getBestTime = 'bestTime';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getAddressId => 'required',
            self::getAddressName => 'required',
            self::getUserId => 'required',
            self::getConsignee => 'required',
            self::getEmail => 'required',
            self::getCountry => 'required',
            self::getProvince => 'required',
            self::getCity => 'required',
            self::getDistrict => 'required',
            self::getAddress => 'required',
            self::getZipcode => 'required',
            self::getTel => 'required',
            self::getMobile => 'required',
            self::getSignBuilding => 'required',
            self::getBestTime => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAddressId.'.required' => '请设置',
            self::getAddressName.'.required' => '请设置地址名称',
            self::getUserId.'.required' => '请设置用户ID',
            self::getConsignee.'.required' => '请设置收货人',
            self::getEmail.'.required' => '请设置邮箱',
            self::getCountry.'.required' => '请设置国家',
            self::getProvince.'.required' => '请设置省份',
            self::getCity.'.required' => '请设置城市',
            self::getDistrict.'.required' => '请设置区县',
            self::getAddress.'.required' => '请设置详细地址',
            self::getZipcode.'.required' => '请设置邮编',
            self::getTel.'.required' => '请设置电话',
            self::getMobile.'.required' => '请设置手机',
            self::getSignBuilding.'.required' => '请设置标志建筑',
            self::getBestTime.'.required' => '请设置最佳送货时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
