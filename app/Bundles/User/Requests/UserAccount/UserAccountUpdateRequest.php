<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserAccount;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getAdminUser,
        self::getAmount,
        self::getAddTime,
        self::getPaidTime,
        self::getAdminNote,
        self::getUserNote,
        self::getProcessType,
        self::getPayment,
        self::getIsPaid,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getAdminUser, description: '管理员', type: 'string'),
        new OA\Property(property: self::getAmount, description: '金额', type: 'string'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getPaidTime, description: '支付时间', type: 'integer'),
        new OA\Property(property: self::getAdminNote, description: '管理员备注', type: 'string'),
        new OA\Property(property: self::getUserNote, description: '用户备注', type: 'string'),
        new OA\Property(property: self::getProcessType, description: '处理类型', type: 'integer'),
        new OA\Property(property: self::getPayment, description: '支付方式', type: 'string'),
        new OA\Property(property: self::getIsPaid, description: '是否已支付', type: 'integer'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserAccountUpdateRequest extends FormRequest
{
    const string getId = 'id';

    const string getUserId = 'userId';

    const string getAdminUser = 'adminUser';

    const string getAmount = 'amount';

    const string getAddTime = 'addTime';

    const string getPaidTime = 'paidTime';

    const string getAdminNote = 'adminNote';

    const string getUserNote = 'userNote';

    const string getProcessType = 'processType';

    const string getPayment = 'payment';

    const string getIsPaid = 'isPaid';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getAdminUser => 'required',
            self::getAmount => 'required',
            self::getAddTime => 'required',
            self::getPaidTime => 'required',
            self::getAdminNote => 'required',
            self::getUserNote => 'required',
            self::getProcessType => 'required',
            self::getPayment => 'required',
            self::getIsPaid => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getAdminUser.'.required' => '请设置管理员',
            self::getAmount.'.required' => '请设置金额',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getPaidTime.'.required' => '请设置支付时间',
            self::getAdminNote.'.required' => '请设置管理员备注',
            self::getUserNote.'.required' => '请设置用户备注',
            self::getProcessType.'.required' => '请设置处理类型',
            self::getPayment.'.required' => '请设置支付方式',
            self::getIsPaid.'.required' => '请设置是否已支付',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
