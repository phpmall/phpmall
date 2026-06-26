<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ShopReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShopReviewReplyRequest',
    required: [
        self::getReplyContent,
    ],
    properties: [
        new OA\Property(property: self::getReplyContent, description: '回复内容', type: 'string', maxLength: 500),
    ]
)]
class ShopReviewReplyRequest extends FormRequest
{
    const string getReplyContent = 'reply_content';

    public function rules(): array
    {
        return [
            self::getReplyContent => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getReplyContent.'.required' => '请填写回复内容',
            self::getReplyContent.'.max' => '回复内容不能超过500个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
