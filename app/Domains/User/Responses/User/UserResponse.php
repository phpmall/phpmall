<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\User;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse')]
class UserResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '', type: 'string')]
    private string $name;

    #[OA\Property(property: 'email', description: '', type: 'string')]
    private string $email;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'phoneVerifiedAt', description: '手机号验证时间', type: 'string')]
    private string $phoneVerifiedAt;

    #[OA\Property(property: 'emailVerifiedAt', description: '', type: 'string')]
    private string $emailVerifiedAt;

    #[OA\Property(property: 'status', description: '状态：1-正常 2-禁用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'avatar', description: '头像', type: 'string')]
    private string $avatar;

    #[OA\Property(property: 'nickname', description: '昵称', type: 'string')]
    private string $nickname;

    #[OA\Property(property: 'rememberToken', description: '', type: 'string')]
    private string $rememberToken;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取手机号
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * 设置手机号
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * 获取手机号验证时间
     */
    public function getPhoneVerifiedAt(): string
    {
        return $this->phoneVerifiedAt;
    }

    /**
     * 设置手机号验证时间
     */
    public function setPhoneVerifiedAt(string $phoneVerifiedAt): void
    {
        $this->phoneVerifiedAt = $phoneVerifiedAt;
    }

    /**
     * 获取
     */
    public function getEmailVerifiedAt(): string
    {
        return $this->emailVerifiedAt;
    }

    /**
     * 设置
     */
    public function setEmailVerifiedAt(string $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * 获取状态：1-正常 2-禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：1-正常 2-禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
     * 获取昵称
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * 设置昵称
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
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
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
