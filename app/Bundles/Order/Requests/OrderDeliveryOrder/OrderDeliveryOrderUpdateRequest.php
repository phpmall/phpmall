<?php

declare(strict_types=1);

namespace App\Bundles\Order\Requests\OrderDeliveryOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderDeliveryOrderUpdateRequest',
    required: [
        self::getDeliveryId,
        self::getDeliverySn,
        self::getOrderSn,
        self::getOrderId,
        self::getInvoiceNo,
        self::getAddTime,
        self::getShippingId,
        self::getShippingName,
        self::getUserId,
        self::getActionUser,
        self::getConsignee,
        self::getAddress,
        self::getCountry,
        self::getProvince,
        self::getCity,
        self::getDistrict,
        self::getSignBuilding,
        self::getEmail,
        self::getZipcode,
        self::getTel,
        self::getMobile,
        self::getBestTime,
        self::getPostscript,
        self::getHowOos,
        self::getInsureFee,
        self::getShippingFee,
        self::getUpdateTime,
        self::getSuppliersId,
        self::getStatus,
        self::getAgencyId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getDeliveryId, description: '', type: 'integer'),
        new OA\Property(property: self::getDeliverySn, description: '发货单号', type: 'string'),
        new OA\Property(property: self::getOrderSn, description: '订单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getInvoiceNo, description: '物流单号', type: 'string'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getShippingId, description: '配送方式ID', type: 'integer'),
        new OA\Property(property: self::getShippingName, description: '配送方式名称', type: 'string'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getActionUser, description: '操作用户', type: 'string'),
        new OA\Property(property: self::getConsignee, description: '收货人', type: 'string'),
        new OA\Property(property: self::getAddress, description: '详细地址', type: 'string'),
        new OA\Property(property: self::getCountry, description: '国家', type: 'integer'),
        new OA\Property(property: self::getProvince, description: '省份', type: 'integer'),
        new OA\Property(property: self::getCity, description: '城市', type: 'integer'),
        new OA\Property(property: self::getDistrict, description: '区县', type: 'integer'),
        new OA\Property(property: self::getSignBuilding, description: '标志建筑', type: 'string'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getZipcode, description: '邮编', type: 'string'),
        new OA\Property(property: self::getTel, description: '电话', type: 'string'),
        new OA\Property(property: self::getMobile, description: '手机', type: 'string'),
        new OA\Property(property: self::getBestTime, description: '最佳送货时间', type: 'string'),
        new OA\Property(property: self::getPostscript, description: '附言', type: 'string'),
        new OA\Property(property: self::getHowOos, description: '缺货处理', type: 'string'),
        new OA\Property(property: self::getInsureFee, description: '保价费用', type: 'string'),
        new OA\Property(property: self::getShippingFee, description: '配送费用', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '更新时间戳', type: 'integer'),
        new OA\Property(property: self::getSuppliersId, description: '供应商ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态', type: 'integer'),
        new OA\Property(property: self::getAgencyId, description: '代理商ID', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class OrderDeliveryOrderUpdateRequest extends FormRequest
{
    const string getDeliveryId = 'deliveryId';

    const string getDeliverySn = 'deliverySn';

    const string getOrderSn = 'orderSn';

    const string getOrderId = 'orderId';

    const string getInvoiceNo = 'invoiceNo';

    const string getAddTime = 'addTime';

    const string getShippingId = 'shippingId';

    const string getShippingName = 'shippingName';

    const string getUserId = 'userId';

    const string getActionUser = 'actionUser';

    const string getConsignee = 'consignee';

    const string getAddress = 'address';

    const string getCountry = 'country';

    const string getProvince = 'province';

    const string getCity = 'city';

    const string getDistrict = 'district';

    const string getSignBuilding = 'signBuilding';

    const string getEmail = 'email';

    const string getZipcode = 'zipcode';

    const string getTel = 'tel';

    const string getMobile = 'mobile';

    const string getBestTime = 'bestTime';

    const string getPostscript = 'postscript';

    const string getHowOos = 'howOos';

    const string getInsureFee = 'insureFee';

    const string getShippingFee = 'shippingFee';

    const string getUpdateTime = 'updateTime';

    const string getSuppliersId = 'suppliersId';

    const string getStatus = 'status';

    const string getAgencyId = 'agencyId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getDeliveryId => 'required',
            self::getDeliverySn => 'required',
            self::getOrderSn => 'required',
            self::getOrderId => 'required',
            self::getInvoiceNo => 'required',
            self::getAddTime => 'required',
            self::getShippingId => 'required',
            self::getShippingName => 'required',
            self::getUserId => 'required',
            self::getActionUser => 'required',
            self::getConsignee => 'required',
            self::getAddress => 'required',
            self::getCountry => 'required',
            self::getProvince => 'required',
            self::getCity => 'required',
            self::getDistrict => 'required',
            self::getSignBuilding => 'required',
            self::getEmail => 'required',
            self::getZipcode => 'required',
            self::getTel => 'required',
            self::getMobile => 'required',
            self::getBestTime => 'required',
            self::getPostscript => 'required',
            self::getHowOos => 'required',
            self::getInsureFee => 'required',
            self::getShippingFee => 'required',
            self::getUpdateTime => 'required',
            self::getSuppliersId => 'required',
            self::getStatus => 'required',
            self::getAgencyId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getDeliveryId.'.required' => '请设置',
            self::getDeliverySn.'.required' => '请设置发货单号',
            self::getOrderSn.'.required' => '请设置订单号',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getInvoiceNo.'.required' => '请设置物流单号',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getShippingId.'.required' => '请设置配送方式ID',
            self::getShippingName.'.required' => '请设置配送方式名称',
            self::getUserId.'.required' => '请设置用户ID',
            self::getActionUser.'.required' => '请设置操作用户',
            self::getConsignee.'.required' => '请设置收货人',
            self::getAddress.'.required' => '请设置详细地址',
            self::getCountry.'.required' => '请设置国家',
            self::getProvince.'.required' => '请设置省份',
            self::getCity.'.required' => '请设置城市',
            self::getDistrict.'.required' => '请设置区县',
            self::getSignBuilding.'.required' => '请设置标志建筑',
            self::getEmail.'.required' => '请设置邮箱',
            self::getZipcode.'.required' => '请设置邮编',
            self::getTel.'.required' => '请设置电话',
            self::getMobile.'.required' => '请设置手机',
            self::getBestTime.'.required' => '请设置最佳送货时间',
            self::getPostscript.'.required' => '请设置附言',
            self::getHowOos.'.required' => '请设置缺货处理',
            self::getInsureFee.'.required' => '请设置保价费用',
            self::getShippingFee.'.required' => '请设置配送费用',
            self::getUpdateTime.'.required' => '请设置更新时间戳',
            self::getSuppliersId.'.required' => '请设置供应商ID',
            self::getStatus.'.required' => '请设置状态',
            self::getAgencyId.'.required' => '请设置代理商ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
