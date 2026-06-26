<?php

declare(strict_types=1);

namespace App\Api\User\Requests\OrderReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderReviewStoreRequest',
    required: [
        self::getOrderId,
        self::getRating,
        self::getContent,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getRating, description: '评分:1-5', type: 'integer', minimum: 1, maximum: 5),
        new OA\Property(property: self::getContent, description: '评价内容', type: 'string'),
        new OA\Property(
            property: self::getImages,
            description: '评价图片',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'uri'),
            nullable: true
        ),
        new OA\Property(property: self::getIsAnonymous, description: '是否匿名:0否，1是', type: 'integer', nullable: true),
    ]
)]
class OrderReviewStoreRequest extends FormRequest
{
    const string getOrderId = 'order_id';

    const string getRating = 'rating';

    const string getContent = 'content';

    const string getImages = 'images';

    const string getIsAnonymous = 'is_anonymous';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getOrderId => ['required', 'integer', 'min:1'],
            self::getRating => ['required', 'integer', 'min:1', 'max:5'],
            self::getContent => ['required', 'string', 'min:5', 'max:2000'],
            self::getImages => ['nullable', 'array', 'max:9'],
            self::getImages.'.*' => ['string', 'url', 'max:500'],
            self::getIsAnonymous => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请选择订单',
            self::getRating.'.required' => '请给出评分',
            self::getContent.'.required' => '请填写评价内容',
            self::getContent.'.min' => '评价内容至少5个字符',
        ];
    }
}
