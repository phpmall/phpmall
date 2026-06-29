<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Withdraw;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerWithdrawIndexRequest',
    properties: [
        new OA\Property(property: self::getStatus, description: '状态', type: 'integer', nullable: true),
        new OA\Property(property: self::getPage, description: '当前页码', type: 'integer', nullable: true),
        new OA\Property(property: self::getPerPage, description: '每页数量', type: 'integer', nullable: true),
    ]
)]
class WithdrawIndexRequest extends FormRequest
{
    const string getStatus = 'status';

    const string getPage = 'page';

    const string getPerPage = 'per_page';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getStatus => ['sometimes', 'nullable', 'integer'],
            self::getPage => ['sometimes', 'nullable', 'integer', 'min:1'],
            self::getPerPage => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getStatus.'.integer' => '状态必须是整数',
            self::getPage.'.integer' => '页码必须是整数',
            self::getPage.'.min' => '页码不能小于1',
            self::getPerPage.'.integer' => '每页数量必须是整数',
            self::getPerPage.'.min' => '每页数量不能小于1',
            self::getPerPage.'.max' => '每页数量不能超过100',
        ];
    }
}
