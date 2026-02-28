<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserExtendInfoEntity')]
class UserExtendInfoEntity
{
    use DTOHelper;

    const string getId = 'Id';

    const string getUserId = 'user_id'; // 用户ID

    const string getRegFieldId = 'reg_field_id'; // 注册字段ID

    const string getContent = 'content'; // 内容

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: '', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'regFieldId', description: '注册字段ID', type: 'integer')]
    private int $regFieldId;

    #[OA\Property(property: 'content', description: '内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取注册字段ID
     */
    public function getRegFieldId(): int
    {
        return $this->regFieldId;
    }

    /**
     * 设置注册字段ID
     */
    public function setRegFieldId(int $regFieldId): void
    {
        $this->regFieldId = $regFieldId;
    }

    /**
     * 获取内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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
