<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Responses\AdminAction;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminActionResponse')]
class AdminActionResponse
{
    use DTOHelper;

    #[OA\Property(property: 'actionId', description: '', type: 'integer')]
    private int $actionId;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'actionCode', description: '权限代码', type: 'string')]
    private string $actionCode;

    #[OA\Property(property: 'relevance', description: '关联信息', type: 'string')]
    private string $relevance;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getActionId(): int
    {
        return $this->actionId;
    }

    /**
     * 设置
     */
    public function setActionId(int $actionId): void
    {
        $this->actionId = $actionId;
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
     * 获取权限代码
     */
    public function getActionCode(): string
    {
        return $this->actionCode;
    }

    /**
     * 设置权限代码
     */
    public function setActionCode(string $actionCode): void
    {
        $this->actionCode = $actionCode;
    }

    /**
     * 获取关联信息
     */
    public function getRelevance(): string
    {
        return $this->relevance;
    }

    /**
     * 设置关联信息
     */
    public function setRelevance(string $relevance): void
    {
        $this->relevance = $relevance;
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
