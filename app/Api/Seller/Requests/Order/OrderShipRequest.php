<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerOrderShipRequest',
    required: [
        self::getLogisticsCompany,
        self::getTrackingNo,
    ],
    properties: [
        new OA\Property(property: self::getLogisticsCompany, description: '物流公司', type: 'string'),
        new OA\Property(property: self::getTrackingNo, description: '物流单号', type: 'string'),
        new OA\Property(property: self::getRemark, description: '发货备注', type: 'string', nullable: true),
    ]
)]
class OrderShipRequest extends FormRequest
{
    const string getLogisticsCompany = 'logistics_company';

    const string getTrackingNo = 'tracking_no';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getLogisticsCompany => 'required|string|max:100',
            self::getTrackingNo => 'required|string|max:100',
            self::getRemark => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogisticsCompany.'.required' => '请选择物流公司',
            self::getTrackingNo.'.required' => '请填写物流单号',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
