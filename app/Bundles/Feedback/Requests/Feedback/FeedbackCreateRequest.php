<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Requests\Feedback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FeedbackCreateRequest',
    required: [
        self::getMsgId,
        self::getParentId,
        self::getUserId,
        self::getUserName,
        self::getUserEmail,
        self::getMsgTitle,
        self::getMsgType,
        self::getMsgStatus,
        self::getMsgContent,
        self::getMsgTime,
        self::getMessageImg,
        self::getOrderId,
        self::getMsgArea,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getMsgId, description: '', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getUserEmail, description: '用户邮箱', type: 'string'),
        new OA\Property(property: self::getMsgTitle, description: '留言标题', type: 'string'),
        new OA\Property(property: self::getMsgType, description: '留言类型', type: 'integer'),
        new OA\Property(property: self::getMsgStatus, description: '留言状态', type: 'integer'),
        new OA\Property(property: self::getMsgContent, description: '留言内容', type: 'string'),
        new OA\Property(property: self::getMsgTime, description: '留言时间', type: 'integer'),
        new OA\Property(property: self::getMessageImg, description: '留言图片', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getMsgArea, description: '留言区域', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class FeedbackCreateRequest extends FormRequest
{
    const string getMsgId = 'msgId';

    const string getParentId = 'parentId';

    const string getUserId = 'userId';

    const string getUserName = 'userName';

    const string getUserEmail = 'userEmail';

    const string getMsgTitle = 'msgTitle';

    const string getMsgType = 'msgType';

    const string getMsgStatus = 'msgStatus';

    const string getMsgContent = 'msgContent';

    const string getMsgTime = 'msgTime';

    const string getMessageImg = 'messageImg';

    const string getOrderId = 'orderId';

    const string getMsgArea = 'msgArea';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getMsgId => 'required',
            self::getParentId => 'required',
            self::getUserId => 'required',
            self::getUserName => 'required',
            self::getUserEmail => 'required',
            self::getMsgTitle => 'required',
            self::getMsgType => 'required',
            self::getMsgStatus => 'required',
            self::getMsgContent => 'required',
            self::getMsgTime => 'required',
            self::getMessageImg => 'required',
            self::getOrderId => 'required',
            self::getMsgArea => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getMsgId.'.required' => '请设置',
            self::getParentId.'.required' => '请设置父级ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getUserName.'.required' => '请设置用户名',
            self::getUserEmail.'.required' => '请设置用户邮箱',
            self::getMsgTitle.'.required' => '请设置留言标题',
            self::getMsgType.'.required' => '请设置留言类型',
            self::getMsgStatus.'.required' => '请设置留言状态',
            self::getMsgContent.'.required' => '请设置留言内容',
            self::getMsgTime.'.required' => '请设置留言时间',
            self::getMessageImg.'.required' => '请设置留言图片',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getMsgArea.'.required' => '请设置留言区域',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
