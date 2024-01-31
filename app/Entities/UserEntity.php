<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserEntity')]
class UserEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'name', description: '昵称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'avatar', description: '头像', type: 'string')]
    protected string $avatar;

    #[OA\Property(property: 'birthday', description: '生日', type: 'string')]
    protected string $birthday;

    #[OA\Property(property: 'mobile', description: '登录手机号', type: 'string')]
    protected string $mobile;

    #[OA\Property(property: 'mobile_verified_at', description: '手机号验证时间', type: 'string')]
    protected string $mobileVerifiedAt;

    #[OA\Property(property: 'password', description: '登录用户密码', type: 'string')]
    protected string $password;

    #[OA\Property(property: 'status', description: '状态:1正常;2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'remember_token', description: '会话令牌', type: 'string')]
    protected string $rememberToken;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取昵称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置昵称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取头像
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * 设置头像
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
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
     * 获取登录手机号
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * 设置登录手机号
     */
    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * 获取手机号验证时间
     */
    public function getMobileVerifiedAt(): string
    {
        return $this->mobileVerifiedAt;
    }

    /**
     * 设置手机号验证时间
     */
    public function setMobileVerifiedAt(string $mobileVerifiedAt): void
    {
        $this->mobileVerifiedAt = $mobileVerifiedAt;
    }

    /**
     * 获取登录用户密码
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * 设置登录用户密码
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * 获取状态:1正常;2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常;2禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取会话令牌
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * 设置会话令牌
     */
    public function setRememberToken(string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
