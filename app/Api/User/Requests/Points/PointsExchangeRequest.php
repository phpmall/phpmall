<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Points;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PointsExchangeRequest',
    required: [
        self::getPoints,
        self::getTargetType,
    ],
    properties: [
        new OA\Property(property: self::getPoints, description: '兑换积分数量', type: 'integer', minimum: 1),
        new OA\Property(property: self::getTargetType, description: '兑换目标类型:coupon,balance,gift', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '兑换目标ID', type: 'integer', nullable: true),
    ]
)]
class PointsExchangeRequest extends FormRequest
{
    const string getPoints = 'points';

    const string getTargetType = 'target_type';

    const string getTargetId = 'target_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getPoints => ['required', 'integer', 'min:1'],
            self::getTargetType => ['required', 'string', 'in:coupon,balance,gift'],
            self::getTargetId => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getPoints.'.required' => '请输入兑换积分数量',
            self::getTargetType.'.required' => '请选择兑换类型',
        ];
    }
}
