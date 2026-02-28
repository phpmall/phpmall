<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserEntity')]
class UserEntity
{
    use DTOHelper;

    const string getUserId = 'user_id';

    const string getEmail = 'email'; // 邮箱

    const string getUserName = 'user_name'; // 用户名

    const string getPassword = 'password'; // 密码

    const string getQuestion = 'question'; // 密保问题

    const string getAnswer = 'answer'; // 密保答案

    const string getSex = 'sex'; // 性别

    const string getBirthday = 'birthday'; // 生日

    const string getUserMoney = 'user_money'; // 用户余额

    const string getFrozenMoney = 'frozen_money'; // 冻结金额

    const string getPayPoints = 'pay_points'; // 消费积分

    const string getRankPoints = 'rank_points'; // 等级积分

    const string getAddressId = 'address_id'; // 默认地址ID

    const string getRegTime = 'reg_time'; // 注册时间

    const string getLastLogin = 'last_login'; // 最后登录时间

    const string getLastTime = 'last_time'; // 最后访问时间

    const string getLastIp = 'last_ip'; // 最后登录IP

    const string getVisitCount = 'visit_count'; // 访问次数

    const string getUserRank = 'user_rank'; // 用户等级

    const string getIsSpecial = 'is_special'; // 是否特殊用户

    const string getEcSalt = 'ec_salt'; // EC盐值

    const string getSalt = 'salt'; // 密码盐值

    const string getParentId = 'parent_id'; // 父级ID

    const string getFlag = 'flag'; // 标志

    const string getAlias = 'alias'; // 用户别名

    const string getMsn = 'msn'; // MSN账号

    const string getQq = 'qq'; // QQ号码

    const string getOfficePhone = 'office_phone'; // 办公电话

    const string getHomePhone = 'home_phone'; // 家庭电话

    const string getMobilePhone = 'mobile_phone'; // 手机号码

    const string getIsValidated = 'is_validated'; // 是否已验证

    const string getCreditLine = 'credit_line'; // 信用额度

    const string getPasswdQuestion = 'passwd_question'; // 密码问题

    const string getPasswdAnswer = 'passwd_answer'; // 密码答案

    const string getRememberToken = 'remember_token';

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'userId', description: '', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'userName', description: '用户名', type: 'string')]
    private string $userName;

    #[OA\Property(property: 'password', description: '密码', type: 'string')]
    private string $password;

    #[OA\Property(property: 'question', description: '密保问题', type: 'string')]
    private string $question;

    #[OA\Property(property: 'answer', description: '密保答案', type: 'string')]
    private string $answer;

    #[OA\Property(property: 'sex', description: '性别', type: 'integer')]
    private int $sex;

    #[OA\Property(property: 'birthday', description: '生日', type: 'string')]
    private string $birthday;

    #[OA\Property(property: 'userMoney', description: '用户余额', type: 'string')]
    private string $userMoney;

    #[OA\Property(property: 'frozenMoney', description: '冻结金额', type: 'string')]
    private string $frozenMoney;

    #[OA\Property(property: 'payPoints', description: '消费积分', type: 'integer')]
    private int $payPoints;

    #[OA\Property(property: 'rankPoints', description: '等级积分', type: 'integer')]
    private int $rankPoints;

    #[OA\Property(property: 'addressId', description: '默认地址ID', type: 'integer')]
    private int $addressId;

    #[OA\Property(property: 'regTime', description: '注册时间', type: 'integer')]
    private int $regTime;

    #[OA\Property(property: 'lastLogin', description: '最后登录时间', type: 'integer')]
    private int $lastLogin;

    #[OA\Property(property: 'lastTime', description: '最后访问时间', type: 'string')]
    private string $lastTime;

    #[OA\Property(property: 'lastIp', description: '最后登录IP', type: 'string')]
    private string $lastIp;

    #[OA\Property(property: 'visitCount', description: '访问次数', type: 'integer')]
    private int $visitCount;

    #[OA\Property(property: 'userRank', description: '用户等级', type: 'integer')]
    private int $userRank;

    #[OA\Property(property: 'isSpecial', description: '是否特殊用户', type: 'integer')]
    private int $isSpecial;

    #[OA\Property(property: 'ecSalt', description: 'EC盐值', type: 'string')]
    private string $ecSalt;

    #[OA\Property(property: 'salt', description: '密码盐值', type: 'string')]
    private string $salt;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'flag', description: '标志', type: 'integer')]
    private int $flag;

    #[OA\Property(property: 'alias', description: '用户别名', type: 'string')]
    private string $alias;

    #[OA\Property(property: 'msn', description: 'MSN账号', type: 'string')]
    private string $msn;

    #[OA\Property(property: 'qq', description: 'QQ号码', type: 'string')]
    private string $qq;

    #[OA\Property(property: 'officePhone', description: '办公电话', type: 'string')]
    private string $officePhone;

    #[OA\Property(property: 'homePhone', description: '家庭电话', type: 'string')]
    private string $homePhone;

    #[OA\Property(property: 'mobilePhone', description: '手机号码', type: 'string')]
    private string $mobilePhone;

    #[OA\Property(property: 'isValidated', description: '是否已验证', type: 'integer')]
    private int $isValidated;

    #[OA\Property(property: 'creditLine', description: '信用额度', type: 'string')]
    private string $creditLine;

    #[OA\Property(property: 'passwdQuestion', description: '密码问题', type: 'string')]
    private string $passwdQuestion;

    #[OA\Property(property: 'passwdAnswer', description: '密码答案', type: 'string')]
    private string $passwdAnswer;

    #[OA\Property(property: 'rememberToken', description: '', type: 'string')]
    private string $rememberToken;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取邮箱
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取用户名
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * 设置用户名
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * 获取密码
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * 设置密码
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * 获取密保问题
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * 设置密保问题
     */
    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    /**
     * 获取密保答案
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * 设置密保答案
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * 获取性别
     */
    public function getSex(): int
    {
        return $this->sex;
    }

    /**
     * 设置性别
     */
    public function setSex(int $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * 获取生日
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * 设置生日
     */
    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * 获取用户余额
     */
    public function getUserMoney(): string
    {
        return $this->userMoney;
    }

    /**
     * 设置用户余额
     */
    public function setUserMoney(string $userMoney): void
    {
        $this->userMoney = $userMoney;
    }

    /**
     * 获取冻结金额
     */
    public function getFrozenMoney(): string
    {
        return $this->frozenMoney;
    }

    /**
     * 设置冻结金额
     */
    public function setFrozenMoney(string $frozenMoney): void
    {
        $this->frozenMoney = $frozenMoney;
    }

    /**
     * 获取消费积分
     */
    public function getPayPoints(): int
    {
        return $this->payPoints;
    }

    /**
     * 设置消费积分
     */
    public function setPayPoints(int $payPoints): void
    {
        $this->payPoints = $payPoints;
    }

    /**
     * 获取等级积分
     */
    public function getRankPoints(): int
    {
        return $this->rankPoints;
    }

    /**
     * 设置等级积分
     */
    public function setRankPoints(int $rankPoints): void
    {
        $this->rankPoints = $rankPoints;
    }

    /**
     * 获取默认地址ID
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * 设置默认地址ID
     */
    public function setAddressId(int $addressId): void
    {
        $this->addressId = $addressId;
    }

    /**
     * 获取注册时间
     */
    public function getRegTime(): int
    {
        return $this->regTime;
    }

    /**
     * 设置注册时间
     */
    public function setRegTime(int $regTime): void
    {
        $this->regTime = $regTime;
    }

    /**
     * 获取最后登录时间
     */
    public function getLastLogin(): int
    {
        return $this->lastLogin;
    }

    /**
     * 设置最后登录时间
     */
    public function setLastLogin(int $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * 获取最后访问时间
     */
    public function getLastTime(): string
    {
        return $this->lastTime;
    }

    /**
     * 设置最后访问时间
     */
    public function setLastTime(string $lastTime): void
    {
        $this->lastTime = $lastTime;
    }

    /**
     * 获取最后登录IP
     */
    public function getLastIp(): string
    {
        return $this->lastIp;
    }

    /**
     * 设置最后登录IP
     */
    public function setLastIp(string $lastIp): void
    {
        $this->lastIp = $lastIp;
    }

    /**
     * 获取访问次数
     */
    public function getVisitCount(): int
    {
        return $this->visitCount;
    }

    /**
     * 设置访问次数
     */
    public function setVisitCount(int $visitCount): void
    {
        $this->visitCount = $visitCount;
    }

    /**
     * 获取用户等级
     */
    public function getUserRank(): int
    {
        return $this->userRank;
    }

    /**
     * 设置用户等级
     */
    public function setUserRank(int $userRank): void
    {
        $this->userRank = $userRank;
    }

    /**
     * 获取是否特殊用户
     */
    public function getIsSpecial(): int
    {
        return $this->isSpecial;
    }

    /**
     * 设置是否特殊用户
     */
    public function setIsSpecial(int $isSpecial): void
    {
        $this->isSpecial = $isSpecial;
    }

    /**
     * 获取EC盐值
     */
    public function getEcSalt(): string
    {
        return $this->ecSalt;
    }

    /**
     * 设置EC盐值
     */
    public function setEcSalt(string $ecSalt): void
    {
        $this->ecSalt = $ecSalt;
    }

    /**
     * 获取密码盐值
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * 设置密码盐值
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取标志
     */
    public function getFlag(): int
    {
        return $this->flag;
    }

    /**
     * 设置标志
     */
    public function setFlag(int $flag): void
    {
        $this->flag = $flag;
    }

    /**
     * 获取用户别名
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * 设置用户别名
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * 获取MSN账号
     */
    public function getMsn(): string
    {
        return $this->msn;
    }

    /**
     * 设置MSN账号
     */
    public function setMsn(string $msn): void
    {
        $this->msn = $msn;
    }

    /**
     * 获取QQ号码
     */
    public function getQq(): string
    {
        return $this->qq;
    }

    /**
     * 设置QQ号码
     */
    public function setQq(string $qq): void
    {
        $this->qq = $qq;
    }

    /**
     * 获取办公电话
     */
    public function getOfficePhone(): string
    {
        return $this->officePhone;
    }

    /**
     * 设置办公电话
     */
    public function setOfficePhone(string $officePhone): void
    {
        $this->officePhone = $officePhone;
    }

    /**
     * 获取家庭电话
     */
    public function getHomePhone(): string
    {
        return $this->homePhone;
    }

    /**
     * 设置家庭电话
     */
    public function setHomePhone(string $homePhone): void
    {
        $this->homePhone = $homePhone;
    }

    /**
     * 获取手机号码
     */
    public function getMobilePhone(): string
    {
        return $this->mobilePhone;
    }

    /**
     * 设置手机号码
     */
    public function setMobilePhone(string $mobilePhone): void
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * 获取是否已验证
     */
    public function getIsValidated(): int
    {
        return $this->isValidated;
    }

    /**
     * 设置是否已验证
     */
    public function setIsValidated(int $isValidated): void
    {
        $this->isValidated = $isValidated;
    }

    /**
     * 获取信用额度
     */
    public function getCreditLine(): string
    {
        return $this->creditLine;
    }

    /**
     * 设置信用额度
     */
    public function setCreditLine(string $creditLine): void
    {
        $this->creditLine = $creditLine;
    }

    /**
     * 获取密码问题
     */
    public function getPasswdQuestion(): string
    {
        return $this->passwdQuestion;
    }

    /**
     * 设置密码问题
     */
    public function setPasswdQuestion(string $passwdQuestion): void
    {
        $this->passwdQuestion = $passwdQuestion;
    }

    /**
     * 获取密码答案
     */
    public function getPasswdAnswer(): string
    {
        return $this->passwdAnswer;
    }

    /**
     * 设置密码答案
     */
    public function setPasswdAnswer(string $passwdAnswer): void
    {
        $this->passwdAnswer = $passwdAnswer;
    }

    /**
     * 获取
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * 设置
     */
    public function setRememberToken(string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}
