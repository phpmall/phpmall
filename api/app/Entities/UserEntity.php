<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserEntity')]
class UserEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'uuid', description: '全局ID', type: 'string')]
    protected string $uuid;

    #[OA\Property(property: 'name', description: '昵称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'avatar', description: '头像', type: 'string')]
    protected string $avatar;

    #[OA\Property(property: 'mobile', description: '手机号码', type: 'string')]
    protected string $mobile;

    #[OA\Property(property: 'mobile_verified_time', description: '手机号验证时间', type: 'string')]
    protected string $mobile_verified_time;

    #[OA\Property(property: 'password', description: '登录密码', type: 'string')]
    protected string $password;

    #[OA\Property(property: 'status', description: '状态:1正常;2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'remember_token', description: '', type: 'string')]
    protected string $remember_token;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '删除时间', type: 'string')]
    protected string $deleted_at;

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
     * 获取全局ID
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * 设置全局ID
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
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
     * 获取手机号码
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * 设置手机号码
     */
    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    /**
     * 获取手机号验证时间
     */
    public function getMobileVerifiedTime(): string
    {
        return $this->mobile_verified_time;
    }

    /**
     * 设置手机号验证时间
     */
    public function setMobileVerifiedTime(string $mobile_verified_time): void
    {
        $this->mobile_verified_time = $mobile_verified_time;
    }

    /**
     * 获取登录密码
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * 设置登录密码
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
     * 获取
     */
    public function getRememberToken(): string
    {
        return $this->remember_token;
    }

    /**
     * 设置
     */
    public function setRememberToken(string $remember_token): void
    {
        $this->remember_token = $remember_token;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deleted_at;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }
}
