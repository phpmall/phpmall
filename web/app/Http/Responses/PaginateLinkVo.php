<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PaginateLinkVo')]
class PaginateLinkVo
{
    use ArrayHelper;

    #[OA\Property(property: 'url', description: '链接URL', type: 'string')]
    private string $url;

    #[OA\Property(property: 'label', description: '页标签', type: 'string')]
    private string $label;

    #[OA\Property(property: 'next', description: '当前页', type: 'bool')]
    private bool $active;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
