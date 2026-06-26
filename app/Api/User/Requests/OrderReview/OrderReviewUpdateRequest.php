<?php

declare(strict_types=1);

namespace App\Api\User\Requests\OrderReview;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderReviewUpdateRequest',
    required: [
        self::getContent,
    ],
    properties: [
        new OA\Property(property: self::getRating, description: '评分:1-5', type: 'integer', minimum: 1, maximum: 5),
        new OA\Property(property: self::getContent, description: '评价内容', type: 'string'),
        new OA\Property(
            property: self::getImages,
            description: '评价图片',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'uri'),
            nullable: true
        ),
    ]
)]
class OrderReviewUpdateRequest extends FormRequest
{
    const string getRating = 'rating';

    const string getContent = 'content';

    const string getImages = 'images';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getRating => ['sometimes', 'integer', 'min:1', 'max:5'],
            self::getContent => ['required', 'string', 'min:5', 'max:2000'],
            self::getImages => ['nullable', 'array', 'max:9'],
            self::getImages.'.*' => ['string', 'url', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getContent.'.required' => '请填写评价内容',
            self::getContent.'.min' => '评价内容至少5个字符',
        ];
    }
}
