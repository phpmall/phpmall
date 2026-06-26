<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Data;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerDataExportRequest',
    required: [
        self::getType,
        self::getStartDate,
        self::getEndDate,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '导出类型:1订单数据,2商品数据,3财务数据', type: 'integer'),
        new OA\Property(property: self::getStartDate, description: '开始日期', type: 'string', format: 'date'),
        new OA\Property(property: self::getEndDate, description: '结束日期', type: 'string', format: 'date'),
        new OA\Property(property: self::getFormat, description: '导出格式:csv,xlsx', type: 'string'),
    ]
)]
class DataExportRequest extends FormRequest
{
    const string getType = 'type';

    const string getStartDate = 'start_date';

    const string getEndDate = 'end_date';

    const string getFormat = 'format';

    public function rules(): array
    {
        return [
            self::getType => 'required|integer|in:1,2,3',
            self::getStartDate => 'required|string|date_format:Y-m-d',
            self::getEndDate => 'required|string|date_format:Y-m-d|after_or_equal:'.self::getStartDate,
            self::getFormat => 'nullable|string|in:csv,xlsx',
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择导出类型',
            self::getType.'.in' => '导出类型不正确',
            self::getStartDate.'.required' => '请选择开始日期',
            self::getStartDate.'.date_format' => '开始日期格式不正确',
            self::getEndDate.'.required' => '请选择结束日期',
            self::getEndDate.'.date_format' => '结束日期格式不正确',
            self::getEndDate.'.after_or_equal' => '结束日期不能早于开始日期',
            self::getFormat.'.in' => '导出格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
