<?php

declare(strict_types=1);

namespace App\Bundles\Email\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'EmailSubscriberEntity')]
class EmailSubscriberEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getEmail = 'email'; // 邮箱地址

    const string getStat = 'stat'; // 状态

    const string getHash = 'hash'; // 哈希值

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'email', description: '邮箱地址', type: 'string')]
    private string $email;

    #[OA\Property(property: 'stat', description: '状态', type: 'integer')]
    private int $stat;

    #[OA\Property(property: 'hash', description: '哈希值', type: 'string')]
    private string $hash;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

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
     * 获取邮箱地址
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱地址
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取状态
     */
    public function getStat(): int
    {
        return $this->stat;
    }

    /**
     * 设置状态
     */
    public function setStat(int $stat): void
    {
        $this->stat = $stat;
    }

    /**
     * 获取哈希值
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * 设置哈希值
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
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
