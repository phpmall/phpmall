<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\Promotion;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerPromotionResponse')]
class PromotionResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '活动ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '活动名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '活动类型:1满减,2满折,3赠品,4包邮', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'rules', description: '活动规则', type: 'object')]
    private array $rules;

    #[OA\Property(property: 'start_time', description: '开始时间', type: 'string', format: 'date-time')]
    private string $startTime;

    #[OA\Property(property: 'end_time', description: '结束时间', type: 'string', format: 'date-time')]
    private string $endTime;

    #[OA\Property(property: 'status', description: '状态:0禁用,1启用', type: 'integer')]
    private int $status;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
