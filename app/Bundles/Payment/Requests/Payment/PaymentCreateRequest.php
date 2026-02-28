<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentCreateRequest',
    required: [
        self::getPayId,
        self::getPayCode,
        self::getPayName,
        self::getPayFee,
        self::getPayDesc,
        self::getPayOrder,
        self::getPayConfig,
        self::getEnabled,
        self::getIsCod,
        self::getIsOnline,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getPayId, description: '', type: 'integer'),
        new OA\Property(property: self::getPayCode, description: '支付方式编码', type: 'string'),
        new OA\Property(property: self::getPayName, description: '支付名称', type: 'string'),
        new OA\Property(property: self::getPayFee, description: '支付手续费', type: 'string'),
        new OA\Property(property: self::getPayDesc, description: '支付描述', type: 'string'),
        new OA\Property(property: self::getPayOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getPayConfig, description: '支付配置', type: 'string'),
        new OA\Property(property: self::getEnabled, description: '是否启用', type: 'integer'),
        new OA\Property(property: self::getIsCod, description: '是否货到付款', type: 'integer'),
        new OA\Property(property: self::getIsOnline, description: '是否在线支付', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class PaymentCreateRequest extends FormRequest
{
    const string getPayId = 'payId';

    const string getPayCode = 'payCode';

    const string getPayName = 'payName';

    const string getPayFee = 'payFee';

    const string getPayDesc = 'payDesc';

    const string getPayOrder = 'payOrder';

    const string getPayConfig = 'payConfig';

    const string getEnabled = 'enabled';

    const string getIsCod = 'isCod';

    const string getIsOnline = 'isOnline';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getPayId => 'required',
            self::getPayCode => 'required',
            self::getPayName => 'required',
            self::getPayFee => 'required',
            self::getPayDesc => 'required',
            self::getPayOrder => 'required',
            self::getPayConfig => 'required',
            self::getEnabled => 'required',
            self::getIsCod => 'required',
            self::getIsOnline => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getPayId.'.required' => '请设置',
            self::getPayCode.'.required' => '请设置支付方式编码',
            self::getPayName.'.required' => '请设置支付名称',
            self::getPayFee.'.required' => '请设置支付手续费',
            self::getPayDesc.'.required' => '请设置支付描述',
            self::getPayOrder.'.required' => '请设置排序',
            self::getPayConfig.'.required' => '请设置支付配置',
            self::getEnabled.'.required' => '请设置是否启用',
            self::getIsCod.'.required' => '请设置是否货到付款',
            self::getIsOnline.'.required' => '请设置是否在线支付',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
