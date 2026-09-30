<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '商品标题', type: 'string'),
        new OA\Property(property: self::getStatus, description: '0=下架 1=上架', type: 'integer'),
        new OA\Property(property: self::getAuditStatus, description: '0=待审核 1=通过 2=拒绝', type: 'integer'),
        new OA\Property(property: self::getCreatedAt, description: '创建时间', type: 'string'),
    ]
)]
class ProductQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTitle = 'title';

    public const string getStatus = 'status';

    public const string getAuditStatus = 'auditStatus';

    public const string getCreatedAt = 'createdAt';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
