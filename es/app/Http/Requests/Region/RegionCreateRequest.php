<?php

declare(strict_types=1);

namespace App\Http\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegionCreateRequest',
    required: [
        'id',
        'parent_id',
        'name',
        'pinyin',
        'first_letter',
        'sort',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父级ID', type: 'integer'),
        new OA\Property(property: 'name', description: '名称', type: 'string'),
        new OA\Property(property: 'pinyin', description: '拼音', type: 'string'),
        new OA\Property(property: 'first_letter', description: '首字母', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer'),
        new OA\Property(property: 'created_at', description: '', type: 'string'),
        new OA\Property(property: 'updated_at', description: '', type: 'string'),
        new OA\Property(property: 'deleted_at', description: '', type: 'string'),
    ]
)]
class RegionCreateRequest extends FormRequest
{
    protected array $rule = [
        'id' => 'require',
        'parent_id' => 'require',
        'name' => 'require',
        'pinyin' => 'require',
        'first_letter' => 'require',
        'sort' => 'require',
        'status' => 'require',
        'created_at' => 'require',
        'updated_at' => 'require',
        'deleted_at' => 'require',
    ];

    protected array $message = [
        'id.require' => '请设置ID',
        'parent_id.require' => '请设置父级ID',
        'name.require' => '请设置名称',
        'pinyin.require' => '请设置拼音',
        'first_letter.require' => '请设置首字母',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:1正常,2禁用',
        'created_at.require' => '请设置',
        'updated_at.require' => '请设置',
        'deleted_at.require' => '请设置',
    ];
}