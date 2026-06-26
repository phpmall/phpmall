<?php

declare(strict_types=1);

namespace App\Api\Portal\Responses\Search;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PortalSearchSuggestResponse')]
class SearchSuggestResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'suggestions', description: '搜索建议列表', type: 'array', items: new OA\Items(type: 'string'))]
    private array $suggestions;

    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }
}
