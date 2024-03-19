<?php

declare(strict_types=1);

namespace App\Http\Responses\User;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse')]
class UserResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'uuid', description: '唯一ID', type: 'string')]
    private string $uuid;

    #[OA\Property(property: 'name', description: '昵称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'avatar', description: '头像', type: 'string')]
    private string $avatar;

    #[OA\Property(property: 'mobile', description: '手机号码', type: 'string')]
    private string $mobile;

    #[OA\Property(property: 'mobileVerifiedTime', description: '手机号验证时间', type: 'string')]
    private string $mobileVerifiedTime;

    #[OA\Property(property: 'rememberToken', description: '', type: 'string')]
    private string $rememberToken;

    #[OA\Property(property: 'createdAt', description: '', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '', type: 'string')]
    private string $deletedAt;

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
     * 获取唯一ID
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * 设置唯一ID
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
        return $this->mobileVerifiedTime;
    }

    /**
     * 设置手机号验证时间
     */
    public function setMobileVerifiedTime(string $mobileVerifiedTime): void
    {
        $this->mobileVerifiedTime = $mobileVerifiedTime;
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