<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Requests\ShopPlugins;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShopPluginsCreateRequest',
    required: [
        self::getCode,
        self::getVersion,
        self::getLibrary,
        self::getAssign,
        self::getInstallDate,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCode, description: '插件编码', type: 'string'),
        new OA\Property(property: self::getVersion, description: '版本号', type: 'string'),
        new OA\Property(property: self::getLibrary, description: '库名', type: 'string'),
        new OA\Property(property: self::getAssign, description: '分配状态', type: 'integer'),
        new OA\Property(property: self::getInstallDate, description: '安装日期', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ShopPluginsCreateRequest extends FormRequest
{
    const string getCode = 'code';

    const string getVersion = 'version';

    const string getLibrary = 'library';

    const string getAssign = 'assign';

    const string getInstallDate = 'installDate';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCode => 'required',
            self::getVersion => 'required',
            self::getLibrary => 'required',
            self::getAssign => 'required',
            self::getInstallDate => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCode.'.required' => '请设置插件编码',
            self::getVersion.'.required' => '请设置版本号',
            self::getLibrary.'.required' => '请设置库名',
            self::getAssign.'.required' => '请设置分配状态',
            self::getInstallDate.'.required' => '请设置安装日期',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
