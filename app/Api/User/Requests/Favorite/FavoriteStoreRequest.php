<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Favorite;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FavoriteStoreRequest',
    required: [
        self::getType,
        self::getTargetId,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '收藏类型:product,shop,article', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '目标ID', type: 'integer'),
    ]
)]
class FavoriteStoreRequest extends FormRequest
{
    const string getType = 'type';

    const string getTargetId = 'target_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:product,shop,article'],
            self::getTargetId => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择收藏类型',
            self::getType.'.in' => '收藏类型不正确',
            self::getTargetId.'.required' => '请选择收藏目标',
        ];
    }
}
