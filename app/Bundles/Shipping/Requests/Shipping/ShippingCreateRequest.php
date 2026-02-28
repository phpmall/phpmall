<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Requests\Shipping;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingCreateRequest',
    required: [
        self::getShippingId,
        self::getShippingCode,
        self::getShippingName,
        self::getShippingDesc,
        self::getInsure,
        self::getSupportCod,
        self::getEnabled,
        self::getShippingPrint,
        self::getPrintBg,
        self::getConfigLabel,
        self::getPrintModel,
        self::getShippingOrder,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getShippingId, description: '', type: 'integer'),
        new OA\Property(property: self::getShippingCode, description: '配送代码', type: 'string'),
        new OA\Property(property: self::getShippingName, description: '配送名称', type: 'string'),
        new OA\Property(property: self::getShippingDesc, description: '配送描述', type: 'string'),
        new OA\Property(property: self::getInsure, description: '保价', type: 'string'),
        new OA\Property(property: self::getSupportCod, description: '是否支持货到付款', type: 'integer'),
        new OA\Property(property: self::getEnabled, description: '是否启用', type: 'integer'),
        new OA\Property(property: self::getShippingPrint, description: '打印模板', type: 'string'),
        new OA\Property(property: self::getPrintBg, description: '打印背景', type: 'string'),
        new OA\Property(property: self::getConfigLabel, description: '配置标签', type: 'string'),
        new OA\Property(property: self::getPrintModel, description: '打印模式', type: 'integer'),
        new OA\Property(property: self::getShippingOrder, description: '排序', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShippingCreateRequest extends FormRequest
{
    const string getShippingId = 'shippingId';

    const string getShippingCode = 'shippingCode';

    const string getShippingName = 'shippingName';

    const string getShippingDesc = 'shippingDesc';

    const string getInsure = 'insure';

    const string getSupportCod = 'supportCod';

    const string getEnabled = 'enabled';

    const string getShippingPrint = 'shippingPrint';

    const string getPrintBg = 'printBg';

    const string getConfigLabel = 'configLabel';

    const string getPrintModel = 'printModel';

    const string getShippingOrder = 'shippingOrder';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getShippingId => 'required',
            self::getShippingCode => 'required',
            self::getShippingName => 'required',
            self::getShippingDesc => 'required',
            self::getInsure => 'required',
            self::getSupportCod => 'required',
            self::getEnabled => 'required',
            self::getShippingPrint => 'required',
            self::getPrintBg => 'required',
            self::getConfigLabel => 'required',
            self::getPrintModel => 'required',
            self::getShippingOrder => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getShippingId.'.required' => '请设置',
            self::getShippingCode.'.required' => '请设置配送代码',
            self::getShippingName.'.required' => '请设置配送名称',
            self::getShippingDesc.'.required' => '请设置配送描述',
            self::getInsure.'.required' => '请设置保价',
            self::getSupportCod.'.required' => '请设置是否支持货到付款',
            self::getEnabled.'.required' => '请设置是否启用',
            self::getShippingPrint.'.required' => '请设置打印模板',
            self::getPrintBg.'.required' => '请设置打印背景',
            self::getConfigLabel.'.required' => '请设置配置标签',
            self::getPrintModel.'.required' => '请设置打印模式',
            self::getShippingOrder.'.required' => '请设置排序',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
