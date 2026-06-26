<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Search;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalSearchHotKeywordsResponse')]
class SearchHotKeywordsResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'keywords', description: '热搜关键词列表', type: 'array', items: new OA\Items(type: 'object', properties: [
        new OA\Property(property: 'keyword', description: '关键词', type: 'string'),
        new OA\Property(property: 'hot_value', description: '热度值', type: 'integer'),
    ]))]
    private array $keywords;

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }
}
