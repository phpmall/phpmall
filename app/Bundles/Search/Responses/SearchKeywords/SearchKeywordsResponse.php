<?php

declare(strict_types=1);

namespace App\Bundles\Search\Responses\SearchKeywords;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SearchKeywordsResponse')]
class SearchKeywordsResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'date', description: '日期', type: 'string')]
    private string $date;

    #[OA\Property(property: 'searchEngine', description: '搜索引擎', type: 'string')]
    private string $searchEngine;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'count', description: '搜索次数', type: 'integer')]
    private int $count;

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
     * 获取日期
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * 设置日期
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * 获取搜索引擎
     */
    public function getSearchEngine(): string
    {
        return $this->searchEngine;
    }

    /**
     * 设置搜索引擎
     */
    public function setSearchEngine(string $searchEngine): void
    {
        $this->searchEngine = $searchEngine;
    }

    /**
     * 获取关键词
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置关键词
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取搜索次数
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * 设置搜索次数
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
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
