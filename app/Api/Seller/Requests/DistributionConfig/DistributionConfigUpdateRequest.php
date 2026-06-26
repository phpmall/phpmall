<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\DistributionConfig;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerDistributionConfigUpdateRequest',
    required: [
        self::getCommissionType,
        self::getCommissionRate,
        self::getLevelConfig,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getCommissionType, description: '佣金类型:1按比例,2按固定金额', type: 'integer'),
        new OA\Property(property: self::getCommissionRate, description: '佣金比例(万分之)', type: 'integer', nullable: true),
        new OA\Property(property: self::getLevelConfig, description: '层级配置', type: 'object', nullable: true),
        new OA\Property(property: self::getStatus, description: '状态:0禁用,1启用', type: 'integer'),
    ]
)]
class DistributionConfigUpdateRequest extends FormRequest
{
    const string getCommissionType = 'commission_type';

    const string getCommissionRate = 'commission_rate';

    const string getLevelConfig = 'level_config';

    const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getCommissionType => 'required|integer|in:1,2',
            self::getCommissionRate => 'nullable|integer|min:0',
            self::getLevelConfig => 'nullable|array',
            self::getStatus => 'required|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCommissionType.'.required' => '请选择佣金类型',
            self::getCommissionType.'.in' => '佣金类型不正确',
            self::getStatus.'.required' => '请选择状态',
            self::getStatus.'.in' => '状态值不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
