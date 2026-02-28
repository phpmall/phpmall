<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommentCreateRequest',
    required: [
        self::getCommentId,
        self::getCommentType,
        self::getIdValue,
        self::getEmail,
        self::getUserName,
        self::getContent,
        self::getCommentRank,
        self::getAddTime,
        self::getIpAddress,
        self::getStatus,
        self::getParentId,
        self::getUserId,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getCommentId, description: '', type: 'integer'),
        new OA\Property(property: self::getCommentType, description: '评论类型', type: 'integer'),
        new OA\Property(property: self::getIdValue, description: '关联ID', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getContent, description: '内容', type: 'string'),
        new OA\Property(property: self::getCommentRank, description: '评论等级', type: 'integer'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getIpAddress, description: 'IP地址', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class CommentCreateRequest extends FormRequest
{
    const string getCommentId = 'commentId';

    const string getCommentType = 'commentType';

    const string getIdValue = 'idValue';

    const string getEmail = 'email';

    const string getUserName = 'userName';

    const string getContent = 'content';

    const string getCommentRank = 'commentRank';

    const string getAddTime = 'addTime';

    const string getIpAddress = 'ipAddress';

    const string getStatus = 'status';

    const string getParentId = 'parentId';

    const string getUserId = 'userId';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getCommentId => 'required',
            self::getCommentType => 'required',
            self::getIdValue => 'required',
            self::getEmail => 'required',
            self::getUserName => 'required',
            self::getContent => 'required',
            self::getCommentRank => 'required',
            self::getAddTime => 'required',
            self::getIpAddress => 'required',
            self::getStatus => 'required',
            self::getParentId => 'required',
            self::getUserId => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCommentId.'.required' => '请设置',
            self::getCommentType.'.required' => '请设置评论类型',
            self::getIdValue.'.required' => '请设置关联ID',
            self::getEmail.'.required' => '请设置邮箱',
            self::getUserName.'.required' => '请设置用户名',
            self::getContent.'.required' => '请设置内容',
            self::getCommentRank.'.required' => '请设置评论等级',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getIpAddress.'.required' => '请设置IP地址',
            self::getStatus.'.required' => '请设置状态',
            self::getParentId.'.required' => '请设置父级ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
