<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Requests\AdminMessage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminMessageUpdateRequest',
    required: [
        self::getMessageId,
        self::getSenderId,
        self::getReceiverId,
        self::getSentTime,
        self::getReadTime,
        self::getReaded,
        self::getDeleted,
        self::getTitle,
        self::getMessage,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getMessageId, description: '', type: 'integer'),
        new OA\Property(property: self::getSenderId, description: '发送者ID', type: 'integer'),
        new OA\Property(property: self::getReceiverId, description: '接收者ID', type: 'integer'),
        new OA\Property(property: self::getSentTime, description: '发送时间', type: 'integer'),
        new OA\Property(property: self::getReadTime, description: '阅读时间', type: 'integer'),
        new OA\Property(property: self::getReaded, description: '是否已读', type: 'integer'),
        new OA\Property(property: self::getDeleted, description: '是否删除', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '消息标题', type: 'string'),
        new OA\Property(property: self::getMessage, description: '消息内容', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class AdminMessageUpdateRequest extends FormRequest
{
    const string getMessageId = 'messageId';

    const string getSenderId = 'senderId';

    const string getReceiverId = 'receiverId';

    const string getSentTime = 'sentTime';

    const string getReadTime = 'readTime';

    const string getReaded = 'readed';

    const string getDeleted = 'deleted';

    const string getTitle = 'title';

    const string getMessage = 'message';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getMessageId => 'required',
            self::getSenderId => 'required',
            self::getReceiverId => 'required',
            self::getSentTime => 'required',
            self::getReadTime => 'required',
            self::getReaded => 'required',
            self::getDeleted => 'required',
            self::getTitle => 'required',
            self::getMessage => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getMessageId.'.required' => '请设置',
            self::getSenderId.'.required' => '请设置发送者ID',
            self::getReceiverId.'.required' => '请设置接收者ID',
            self::getSentTime.'.required' => '请设置发送时间',
            self::getReadTime.'.required' => '请设置阅读时间',
            self::getReaded.'.required' => '请设置是否已读',
            self::getDeleted.'.required' => '请设置是否删除',
            self::getTitle.'.required' => '请设置消息标题',
            self::getMessage.'.required' => '请设置消息内容',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
