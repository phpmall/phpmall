<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminAction;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminActionUpdateRequest',
    required: [
        self::getActionId,
        self::getParentId,
        self::getActionCode,
        self::getRelevance,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getActionId, description: '', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getActionCode, description: '权限代码', type: 'string'),
        new OA\Property(property: self::getRelevance, description: '关联信息', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdminActionUpdateRequest extends FormRequest
{
    const string getActionId = 'actionId';

    const string getParentId = 'parentId';

    const string getActionCode = 'actionCode';

    const string getRelevance = 'relevance';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getActionId => 'required',
            self::getParentId => 'required',
            self::getActionCode => 'required',
            self::getRelevance => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getActionId.'.required' => '请设置',
            self::getParentId.'.required' => '请设置父级ID',
            self::getActionCode.'.required' => '请设置权限代码',
            self::getRelevance.'.required' => '请设置关联信息',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
