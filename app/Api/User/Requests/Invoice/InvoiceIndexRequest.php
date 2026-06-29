<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'InvoiceIndexRequest',
    properties: [
        new OA\Property(property: self::getType, description: '发票类型', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '页码', type: 'integer', example: 1),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', example: 20),
    ]
)]
class InvoiceIndexRequest extends FormRequest
{
    const string getType = 'type';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['sometimes', 'integer'],
            self::getPage => ['sometimes', 'integer', 'min:1'],
            self::getPerPage => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
