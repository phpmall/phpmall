<?php

declare(strict_types=1);

namespace App\Api\Supplier\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SupplierPurchaseOrderShipRequest',
    required: [
        self::getLogisticsCompany,
        self::getLogisticsNo,
    ],
    properties: [
        new OA\Property(property: self::getLogisticsCompany, description: '物流公司', type: 'string'),
        new OA\Property(property: self::getLogisticsNo, description: '物流单号', type: 'string'),
        new OA\Property(property: self::getRemark, description: '发货备注', type: 'string', nullable: true),
    ]
)]
class ShipRequest extends FormRequest
{
    const string getLogisticsCompany = 'logistics_company';

    const string getLogisticsNo = 'logistics_no';

    const string getRemark = 'remark';

    public function rules(): array
    {
        return [
            self::getLogisticsCompany => ['required', 'string', 'max:100'],
            self::getLogisticsNo => ['required', 'string', 'max:100'],
            self::getRemark => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getLogisticsCompany.'.required' => '请填写物流公司',
            self::getLogisticsCompany.'.max' => '物流公司不能超过100个字符',
            self::getLogisticsNo.'.required' => '请填写物流单号',
            self::getLogisticsNo.'.max' => '物流单号不能超过100个字符',
            self::getRemark.'.max' => '备注不能超过500个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
