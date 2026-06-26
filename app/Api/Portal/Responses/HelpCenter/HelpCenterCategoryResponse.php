<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\HelpCenter;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalHelpCenterCategoryResponse')]
class HelpCenterCategoryResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '分类ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '分类名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'article_count', description: '文章数量', type: 'integer')]
    private int $articleCount;

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

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getArticleCount(): int
    {
        return $this->articleCount;
    }

    public function setArticleCount(int $articleCount): void
    {
        $this->articleCount = $articleCount;
    }
}
