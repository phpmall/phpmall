<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminUserEntity')]
class AdminUserEntity
{
    use DTOHelper;

    const string getUserId = 'user_id';

    const string getUserName = 'user_name'; // 用户名

    const string getEmail = 'email'; // 邮箱

    const string getPassword = 'password'; // 密码

    const string getEcSalt = 'ec_salt'; // EC盐值

    const string getAddTime = 'add_time'; // 添加时间

    const string getLastLogin = 'last_login'; // 最后登录时间

    const string getLastIp = 'last_ip'; // 最后登录IP

    const string getActionList = 'action_list'; // 操作列表

    const string getNavList = 'nav_list'; // 导航列表

    const string getLangType = 'lang_type'; // 语言类型

    const string getAgencyId = 'agency_id'; // 办事处ID

    const string getSuppliersId = 'suppliers_id'; // 供应商ID

    const string getTodolist = 'todolist'; // 待办事项

    const string getRoleId = 'role_id'; // 角色ID

    const string getRememberToken = 'remember_token';

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'userId', description: '', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'userName', description: '用户名', type: 'string')]
    private string $userName;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'password', description: '密码', type: 'string')]
    private string $password;

    #[OA\Property(property: 'ecSalt', description: 'EC盐值', type: 'string')]
    private string $ecSalt;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'lastLogin', description: '最后登录时间', type: 'integer')]
    private int $lastLogin;

    #[OA\Property(property: 'lastIp', description: '最后登录IP', type: 'string')]
    private string $lastIp;

    #[OA\Property(property: 'actionList', description: '操作列表', type: 'string')]
    private string $actionList;

    #[OA\Property(property: 'navList', description: '导航列表', type: 'string')]
    private string $navList;

    #[OA\Property(property: 'langType', description: '语言类型', type: 'string')]
    private string $langType;

    #[OA\Property(property: 'agencyId', description: '办事处ID', type: 'integer')]
    private int $agencyId;

    #[OA\Property(property: 'suppliersId', description: '供应商ID', type: 'integer')]
    private int $suppliersId;

    #[OA\Property(property: 'todolist', description: '待办事项', type: 'string')]
    private string $todolist;

    #[OA\Property(property: 'roleId', description: '角色ID', type: 'integer')]
    private int $roleId;

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
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
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
     * 获取操作列表
     */
    public function getActionList(): string
    {
        return $this->actionList;
    }

    /**
     * 设置操作列表
     */
    public function setActionList(string $actionList): void
    {
        $this->actionList = $actionList;
    }

    /**
     * 获取导航列表
     */
    public function getNavList(): string
    {
        return $this->navList;
    }

    /**
     * 设置导航列表
     */
    public function setNavList(string $navList): void
    {
        $this->navList = $navList;
    }

    /**
     * 获取语言类型
     */
    public function getLangType(): string
    {
        return $this->langType;
    }

    /**
     * 设置语言类型
     */
    public function setLangType(string $langType): void
    {
        $this->langType = $langType;
    }

    /**
     * 获取办事处ID
     */
    public function getAgencyId(): int
    {
        return $this->agencyId;
    }

    /**
     * 设置办事处ID
     */
    public function setAgencyId(int $agencyId): void
    {
        $this->agencyId = $agencyId;
    }

    /**
     * 获取供应商ID
     */
    public function getSuppliersId(): int
    {
        return $this->suppliersId;
    }

    /**
     * 设置供应商ID
     */
    public function setSuppliersId(int $suppliersId): void
    {
        $this->suppliersId = $suppliersId;
    }

    /**
     * 获取待办事项
     */
    public function getTodolist(): string
    {
        return $this->todolist;
    }

    /**
     * 设置待办事项
     */
    public function setTodolist(string $todolist): void
    {
        $this->todolist = $todolist;
    }

    /**
     * 获取角色ID
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * 设置角色ID
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
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
