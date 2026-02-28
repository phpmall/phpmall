<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\UserBooking;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserBookingCreateRequest',
    required: [
        self::getRecId,
        self::getUserId,
        self::getEmail,
        self::getLinkMan,
        self::getTel,
        self::getGoodsId,
        self::getGoodsDesc,
        self::getGoodsNumber,
        self::getBookingTime,
        self::getIsDispose,
        self::getDisposeUser,
        self::getDisposeTime,
        self::getDisposeNote,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getRecId, description: '', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getLinkMan, description: '联系人', type: 'string'),
        new OA\Property(property: self::getTel, description: '电话', type: 'string'),
        new OA\Property(property: self::getGoodsId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getGoodsDesc, description: '商品描述', type: 'string'),
        new OA\Property(property: self::getGoodsNumber, description: '商品数量', type: 'integer'),
        new OA\Property(property: self::getBookingTime, description: '预定时间', type: 'integer'),
        new OA\Property(property: self::getIsDispose, description: '是否处理', type: 'integer'),
        new OA\Property(property: self::getDisposeUser, description: '处理用户', type: 'string'),
        new OA\Property(property: self::getDisposeTime, description: '处理时间', type: 'integer'),
        new OA\Property(property: self::getDisposeNote, description: '处理备注', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserBookingCreateRequest extends FormRequest
{
    const string getRecId = 'recId';

    const string getUserId = 'userId';

    const string getEmail = 'email';

    const string getLinkMan = 'linkMan';

    const string getTel = 'tel';

    const string getGoodsId = 'goodsId';

    const string getGoodsDesc = 'goodsDesc';

    const string getGoodsNumber = 'goodsNumber';

    const string getBookingTime = 'bookingTime';

    const string getIsDispose = 'isDispose';

    const string getDisposeUser = 'disposeUser';

    const string getDisposeTime = 'disposeTime';

    const string getDisposeNote = 'disposeNote';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getRecId => 'required',
            self::getUserId => 'required',
            self::getEmail => 'required',
            self::getLinkMan => 'required',
            self::getTel => 'required',
            self::getGoodsId => 'required',
            self::getGoodsDesc => 'required',
            self::getGoodsNumber => 'required',
            self::getBookingTime => 'required',
            self::getIsDispose => 'required',
            self::getDisposeUser => 'required',
            self::getDisposeTime => 'required',
            self::getDisposeNote => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getRecId.'.required' => '请设置',
            self::getUserId.'.required' => '请设置用户ID',
            self::getEmail.'.required' => '请设置邮箱',
            self::getLinkMan.'.required' => '请设置联系人',
            self::getTel.'.required' => '请设置电话',
            self::getGoodsId.'.required' => '请设置商品ID',
            self::getGoodsDesc.'.required' => '请设置商品描述',
            self::getGoodsNumber.'.required' => '请设置商品数量',
            self::getBookingTime.'.required' => '请设置预定时间',
            self::getIsDispose.'.required' => '请设置是否处理',
            self::getDisposeUser.'.required' => '请设置处理用户',
            self::getDisposeTime.'.required' => '请设置处理时间',
            self::getDisposeNote.'.required' => '请设置处理备注',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
