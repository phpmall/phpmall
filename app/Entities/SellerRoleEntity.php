<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerRoleEntity')]
class SellerRoleEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'seller_id', description: '商户管理员ID', type: 'integer')]
    protected int $seller_id;

    #[OA\Property(property: 'role_id', description: '角色ID', type: 'integer')]
    protected int $role_id;

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
        return $this->seller_id;
    }

    /**
     * 设置商户管理员ID
     */
    public function setSellerId(int $seller_id): void
    {
        $this->seller_id = $seller_id;
    }

    /**
     * 获取角色ID
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * 设置角色ID
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }
}
