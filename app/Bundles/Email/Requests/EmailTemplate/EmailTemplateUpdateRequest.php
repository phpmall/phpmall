<?php

declare(strict_types=1);

namespace App\Bundles\Email\Requests\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmailTemplateUpdateRequest',
    required: [
        self::getTemplateId,
        self::getType,
        self::getTemplateCode,
        self::getIsHtml,
        self::getTemplateSubject,
        self::getTemplateContent,
        self::getLastModify,
        self::getLastSend,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getTemplateId, description: '', type: 'integer'),
        new OA\Property(property: self::getType, description: '类型', type: 'string'),
        new OA\Property(property: self::getTemplateCode, description: '模板代码', type: 'string'),
        new OA\Property(property: self::getIsHtml, description: '是否HTML格式', type: 'integer'),
        new OA\Property(property: self::getTemplateSubject, description: '模板主题', type: 'string'),
        new OA\Property(property: self::getTemplateContent, description: '模板内容', type: 'string'),
        new OA\Property(property: self::getLastModify, description: '最后修改时间', type: 'integer'),
        new OA\Property(property: self::getLastSend, description: '最后发送时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class EmailTemplateUpdateRequest extends FormRequest
{
    const string getTemplateId = 'templateId';

    const string getType = 'type';

    const string getTemplateCode = 'templateCode';

    const string getIsHtml = 'isHtml';

    const string getTemplateSubject = 'templateSubject';

    const string getTemplateContent = 'templateContent';

    const string getLastModify = 'lastModify';

    const string getLastSend = 'lastSend';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getTemplateId => 'required',
            self::getType => 'required',
            self::getTemplateCode => 'required',
            self::getIsHtml => 'required',
            self::getTemplateSubject => 'required',
            self::getTemplateContent => 'required',
            self::getLastModify => 'required',
            self::getLastSend => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTemplateId.'.required' => '请设置',
            self::getType.'.required' => '请设置类型',
            self::getTemplateCode.'.required' => '请设置模板代码',
            self::getIsHtml.'.required' => '请设置是否HTML格式',
            self::getTemplateSubject.'.required' => '请设置模板主题',
            self::getTemplateContent.'.required' => '请设置模板内容',
            self::getLastModify.'.required' => '请设置最后修改时间',
            self::getLastSend.'.required' => '请设置最后发送时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
