<?php

declare(strict_types=1);

namespace App\Entities;

use App\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentModelEntity')]
class ContentModelEntity
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'name', description: '模型名称', type: 'string')]
    protected string $name;

    #[OA\Property(property: 'code', description: '模型编码', type: 'string')]
    protected string $code;

    #[OA\Property(property: 'intro', description: '模型描述', type: 'string')]
    protected string $intro;

    #[OA\Property(property: 'fields', description: '模型附加字段', type: 'string')]
    protected string $fields;

    #[OA\Property(property: 'immutable', description: '系统模型:1是，2否', type: 'integer')]
    protected int $immutable;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $created_at;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updated_at;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
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
     * 获取模型名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置模型名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取模型编码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置模型编码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取模型描述
     */
    public function getIntro(): string
    {
        return $this->intro;
    }

    /**
     * 设置模型描述
     */
    public function setIntro(string $intro): void
    {
        $this->intro = $intro;
    }

    /**
     * 获取模型附加字段
     */
    public function getFields(): string
    {
        return $this->fields;
    }

    /**
     * 设置模型附加字段
     */
    public function setFields(string $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * 获取系统模型:1是，2否
     */
    public function getImmutable(): int
    {
        return $this->immutable;
    }

    /**
     * 设置系统模型:1是，2否
     */
    public function setImmutable(int $immutable): void
    {
        $this->immutable = $immutable;
    }

    /**
     * 获取状态:1正常,2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常,2禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
