<?php

declare(strict_types=1);

namespace App\Bundles\Email\Requests\EmailSend;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmailSendUpdateRequest',
    required: [
        self::getId,
        self::getEmail,
        self::getTemplateId,
        self::getEmailContent,
        self::getError,
        self::getPri,
        self::getLastSend,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '邮箱地址', type: 'string'),
        new OA\Property(property: self::getTemplateId, description: '模板ID', type: 'integer'),
        new OA\Property(property: self::getEmailContent, description: '邮件内容', type: 'string'),
        new OA\Property(property: self::getError, description: '是否错误', type: 'integer'),
        new OA\Property(property: self::getPri, description: '优先级', type: 'integer'),
        new OA\Property(property: self::getLastSend, description: '最后发送时间', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class EmailSendUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getEmail = 'email';

    const string getTemplateId = 'templateId';

    const string getEmailContent = 'emailContent';

    const string getError = 'error';

    const string getPri = 'pri';

    const string getLastSend = 'lastSend';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getEmail => 'required',
            self::getTemplateId => 'required',
            self::getEmailContent => 'required',
            self::getError => 'required',
            self::getPri => 'required',
            self::getLastSend => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getEmail.'.required' => '请设置邮箱地址',
            self::getTemplateId.'.required' => '请设置模板ID',
            self::getEmailContent.'.required' => '请设置邮件内容',
            self::getError.'.required' => '请设置是否错误',
            self::getPri.'.required' => '请设置优先级',
            self::getLastSend.'.required' => '请设置最后发送时间',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
