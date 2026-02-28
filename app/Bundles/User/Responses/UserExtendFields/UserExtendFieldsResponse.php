<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses\UserExtendFields;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserExtendFieldsResponse')]
class UserExtendFieldsResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'regFieldName', description: '注册字段名称', type: 'string')]
    private string $regFieldName;

    #[OA\Property(property: 'disOrder', description: '显示顺序', type: 'integer')]
    private int $disOrder;

    #[OA\Property(property: 'display', description: '是否显示', type: 'integer')]
    private int $display;

    #[OA\Property(property: 'type', description: '类型', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'isNeed', description: '是否必填', type: 'integer')]
    private int $isNeed;

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
     * 获取注册字段名称
     */
    public function getRegFieldName(): string
    {
        return $this->regFieldName;
    }

    /**
     * 设置注册字段名称
     */
    public function setRegFieldName(string $regFieldName): void
    {
        $this->regFieldName = $regFieldName;
    }

    /**
     * 获取显示顺序
     */
    public function getDisOrder(): int
    {
        return $this->disOrder;
    }

    /**
     * 设置显示顺序
     */
    public function setDisOrder(int $disOrder): void
    {
        $this->disOrder = $disOrder;
    }

    /**
     * 获取是否显示
     */
    public function getDisplay(): int
    {
        return $this->display;
    }

    /**
     * 设置是否显示
     */
    public function setDisplay(int $display): void
    {
        $this->display = $display;
    }

    /**
     * 获取类型
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置类型
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取是否必填
     */
    public function getIsNeed(): int
    {
        return $this->isNeed;
    }

    /**
     * 设置是否必填
     */
    public function setIsNeed(int $isNeed): void
    {
        $this->isNeed = $isNeed;
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
