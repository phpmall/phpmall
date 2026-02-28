<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopErrorLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopErrorLogCreateRequest',
    required: [
        self::getInfo,
        self::getFile,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getInfo, description: '错误信息', type: 'string'),
        new OA\Property(property: self::getFile, description: '错误文件', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopErrorLogCreateRequest extends FormRequest
{
    const string getInfo = 'info';

    const string getFile = 'file';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getInfo => 'required',
            self::getFile => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getInfo.'.required' => '请设置错误信息',
            self::getFile.'.required' => '请设置错误文件',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
