<?php

declare(strict_types=1);

namespace App\Bundles\User\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserCreateRequest',
    required: [
        self::getUserId,
        self::getEmail,
        self::getUserName,
        self::getPassword,
        self::getQuestion,
        self::getAnswer,
        self::getSex,
        self::getBirthday,
        self::getUserMoney,
        self::getFrozenMoney,
        self::getPayPoints,
        self::getRankPoints,
        self::getAddressId,
        self::getRegTime,
        self::getLastLogin,
        self::getLastTime,
        self::getLastIp,
        self::getVisitCount,
        self::getUserRank,
        self::getIsSpecial,
        self::getEcSalt,
        self::getSalt,
        self::getParentId,
        self::getFlag,
        self::getAlias,
        self::getMsn,
        self::getQq,
        self::getOfficePhone,
        self::getHomePhone,
        self::getMobilePhone,
        self::getIsValidated,
        self::getCreditLine,
        self::getPasswdQuestion,
        self::getPasswdAnswer,
        self::getRememberToken,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '', type: 'integer'),
        new OA\Property(property: self::getEmail, description: '邮箱', type: 'string'),
        new OA\Property(property: self::getUserName, description: '用户名', type: 'string'),
        new OA\Property(property: self::getPassword, description: '密码', type: 'string'),
        new OA\Property(property: self::getQuestion, description: '密保问题', type: 'string'),
        new OA\Property(property: self::getAnswer, description: '密保答案', type: 'string'),
        new OA\Property(property: self::getSex, description: '性别', type: 'integer'),
        new OA\Property(property: self::getBirthday, description: '生日', type: 'string'),
        new OA\Property(property: self::getUserMoney, description: '用户余额', type: 'string'),
        new OA\Property(property: self::getFrozenMoney, description: '冻结金额', type: 'string'),
        new OA\Property(property: self::getPayPoints, description: '消费积分', type: 'integer'),
        new OA\Property(property: self::getRankPoints, description: '等级积分', type: 'integer'),
        new OA\Property(property: self::getAddressId, description: '默认地址ID', type: 'integer'),
        new OA\Property(property: self::getRegTime, description: '注册时间', type: 'integer'),
        new OA\Property(property: self::getLastLogin, description: '最后登录时间', type: 'integer'),
        new OA\Property(property: self::getLastTime, description: '最后访问时间', type: 'string'),
        new OA\Property(property: self::getLastIp, description: '最后登录IP', type: 'string'),
        new OA\Property(property: self::getVisitCount, description: '访问次数', type: 'integer'),
        new OA\Property(property: self::getUserRank, description: '用户等级', type: 'integer'),
        new OA\Property(property: self::getIsSpecial, description: '是否特殊用户', type: 'integer'),
        new OA\Property(property: self::getEcSalt, description: 'EC盐值', type: 'string'),
        new OA\Property(property: self::getSalt, description: '密码盐值', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID', type: 'integer'),
        new OA\Property(property: self::getFlag, description: '标志', type: 'integer'),
        new OA\Property(property: self::getAlias, description: '用户别名', type: 'string'),
        new OA\Property(property: self::getMsn, description: 'MSN账号', type: 'string'),
        new OA\Property(property: self::getQq, description: 'QQ号码', type: 'string'),
        new OA\Property(property: self::getOfficePhone, description: '办公电话', type: 'string'),
        new OA\Property(property: self::getHomePhone, description: '家庭电话', type: 'string'),
        new OA\Property(property: self::getMobilePhone, description: '手机号码', type: 'string'),
        new OA\Property(property: self::getIsValidated, description: '是否已验证', type: 'integer'),
        new OA\Property(property: self::getCreditLine, description: '信用额度', type: 'string'),
        new OA\Property(property: self::getPasswdQuestion, description: '密码问题', type: 'string'),
        new OA\Property(property: self::getPasswdAnswer, description: '密码答案', type: 'string'),
        new OA\Property(property: self::getRememberToken, description: '', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class UserCreateRequest extends FormRequest
{
    const string getUserId = 'userId';

    const string getEmail = 'email';

    const string getUserName = 'userName';

    const string getPassword = 'password';

    const string getQuestion = 'question';

    const string getAnswer = 'answer';

    const string getSex = 'sex';

    const string getBirthday = 'birthday';

    const string getUserMoney = 'userMoney';

    const string getFrozenMoney = 'frozenMoney';

    const string getPayPoints = 'payPoints';

    const string getRankPoints = 'rankPoints';

    const string getAddressId = 'addressId';

    const string getRegTime = 'regTime';

    const string getLastLogin = 'lastLogin';

    const string getLastTime = 'lastTime';

    const string getLastIp = 'lastIp';

    const string getVisitCount = 'visitCount';

    const string getUserRank = 'userRank';

    const string getIsSpecial = 'isSpecial';

    const string getEcSalt = 'ecSalt';

    const string getSalt = 'salt';

    const string getParentId = 'parentId';

    const string getFlag = 'flag';

    const string getAlias = 'alias';

    const string getMsn = 'msn';

    const string getQq = 'qq';

    const string getOfficePhone = 'officePhone';

    const string getHomePhone = 'homePhone';

    const string getMobilePhone = 'mobilePhone';

    const string getIsValidated = 'isValidated';

    const string getCreditLine = 'creditLine';

    const string getPasswdQuestion = 'passwdQuestion';

    const string getPasswdAnswer = 'passwdAnswer';

    const string getRememberToken = 'rememberToken';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getEmail => 'required',
            self::getUserName => 'required',
            self::getPassword => 'required',
            self::getQuestion => 'required',
            self::getAnswer => 'required',
            self::getSex => 'required',
            self::getBirthday => 'required',
            self::getUserMoney => 'required',
            self::getFrozenMoney => 'required',
            self::getPayPoints => 'required',
            self::getRankPoints => 'required',
            self::getAddressId => 'required',
            self::getRegTime => 'required',
            self::getLastLogin => 'required',
            self::getLastTime => 'required',
            self::getLastIp => 'required',
            self::getVisitCount => 'required',
            self::getUserRank => 'required',
            self::getIsSpecial => 'required',
            self::getEcSalt => 'required',
            self::getSalt => 'required',
            self::getParentId => 'required',
            self::getFlag => 'required',
            self::getAlias => 'required',
            self::getMsn => 'required',
            self::getQq => 'required',
            self::getOfficePhone => 'required',
            self::getHomePhone => 'required',
            self::getMobilePhone => 'required',
            self::getIsValidated => 'required',
            self::getCreditLine => 'required',
            self::getPasswdQuestion => 'required',
            self::getPasswdAnswer => 'required',
            self::getRememberToken => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置',
            self::getEmail.'.required' => '请设置邮箱',
            self::getUserName.'.required' => '请设置用户名',
            self::getPassword.'.required' => '请设置密码',
            self::getQuestion.'.required' => '请设置密保问题',
            self::getAnswer.'.required' => '请设置密保答案',
            self::getSex.'.required' => '请设置性别',
            self::getBirthday.'.required' => '请设置生日',
            self::getUserMoney.'.required' => '请设置用户余额',
            self::getFrozenMoney.'.required' => '请设置冻结金额',
            self::getPayPoints.'.required' => '请设置消费积分',
            self::getRankPoints.'.required' => '请设置等级积分',
            self::getAddressId.'.required' => '请设置默认地址ID',
            self::getRegTime.'.required' => '请设置注册时间',
            self::getLastLogin.'.required' => '请设置最后登录时间',
            self::getLastTime.'.required' => '请设置最后访问时间',
            self::getLastIp.'.required' => '请设置最后登录IP',
            self::getVisitCount.'.required' => '请设置访问次数',
            self::getUserRank.'.required' => '请设置用户等级',
            self::getIsSpecial.'.required' => '请设置是否特殊用户',
            self::getEcSalt.'.required' => '请设置EC盐值',
            self::getSalt.'.required' => '请设置密码盐值',
            self::getParentId.'.required' => '请设置父级ID',
            self::getFlag.'.required' => '请设置标志',
            self::getAlias.'.required' => '请设置用户别名',
            self::getMsn.'.required' => '请设置MSN账号',
            self::getQq.'.required' => '请设置QQ号码',
            self::getOfficePhone.'.required' => '请设置办公电话',
            self::getHomePhone.'.required' => '请设置家庭电话',
            self::getMobilePhone.'.required' => '请设置手机号码',
            self::getIsValidated.'.required' => '请设置是否已验证',
            self::getCreditLine.'.required' => '请设置信用额度',
            self::getPasswdQuestion.'.required' => '请设置密码问题',
            self::getPasswdAnswer.'.required' => '请设置密码答案',
            self::getRememberToken.'.required' => '请设置',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
