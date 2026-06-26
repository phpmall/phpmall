<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\SubAccount;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerSubAccountResponse')]
class SubAccountResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '子账号ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'username', description: '用户名', type: 'string')]
    private string $username;

    #[OA\Property(property: 'real_name', description: '真实姓名', type: 'string')]
    private string $realName;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string', nullable: true)]
    private ?string $email;

    #[OA\Property(property: 'role_ids', description: '角色ID列表', type: 'array', items: new OA\Items(type: 'integer'))]
    private array $roleIds;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'last_login_at', description: '最后登录时间', type: 'string', format: 'date-time', nullable: true)]
    private ?string $lastLoginAt;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getRealName(): string
    {
        return $this->realName;
    }

    public function setRealName(string $realName): void
    {
        $this->realName = $realName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getRoleIds(): array
    {
        return $this->roleIds;
    }

    public function setRoleIds(array $roleIds): void
    {
        $this->roleIds = $roleIds;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getLastLoginAt(): ?string
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?string $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
