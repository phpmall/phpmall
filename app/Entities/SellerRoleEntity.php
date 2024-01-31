<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Generator\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerRoleEntity')]
class SellerRoleEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'seller_id', description: '商户管理员ID', type: 'integer')]
    protected int $sellerId;

    #[OA\Property(property: 'role_id', description: '角色ID', type: 'integer')]
    protected int $roleId;

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
     * 获取商户管理员ID
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * 设置商户管理员ID
     */
    public function setSellerId(int $sellerId): void
    {
        $this->sellerId = $sellerId;
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
}
