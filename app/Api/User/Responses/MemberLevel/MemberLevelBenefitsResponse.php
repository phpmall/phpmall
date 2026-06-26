<?php

declare(strict_types=1);

namespace App\Api\User\Responses\MemberLevel;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MemberLevelBenefitsResponse')]
class MemberLevelBenefitsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'level_id', description: '等级ID', type: 'integer')]
    private int $levelId;

    #[OA\Property(property: 'level_name', description: '等级名称', type: 'string')]
    private string $levelName;

    #[OA\Property(
        property: 'benefits',
        description: '权益列表',
        type: 'array',
        items: new OA\Items(type: 'object', properties: [
            new OA\Property(property: 'id', type: 'integer', description: '权益ID'),
            new OA\Property(property: 'name', type: 'string', description: '权益名称'),
            new OA\Property(property: 'description', type: 'string', description: '权益描述'),
            new OA\Property(property: 'icon', type: 'string', description: '权益图标'),
            new OA\Property(property: 'is_available', type: 'integer', description: '是否可用:0否，1是'),
        ])
    )]
    private array $benefits;

    public function getLevelId(): int
    {
        return $this->levelId;
    }

    public function setLevelId(int $levelId): void
    {
        $this->levelId = $levelId;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function setLevelName(string $levelName): void
    {
        $this->levelName = $levelName;
    }

    public function getBenefits(): array
    {
        return $this->benefits;
    }

    public function setBenefits(array $benefits): void
    {
        $this->benefits = $benefits;
    }
}
