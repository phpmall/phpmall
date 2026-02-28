<?php

declare(strict_types=1);

namespace App\Bundles\User\Responses\UserExtendInfo;

use Juling\Foundation\Http\Responses\PaginateLinkVo;
use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserExtendInfoQueryResponse')]
class UserExtendInfoQueryResponse
{
    use DTOHelper;

    #[OA\Property(property: 'currentPage', description: '当前页码', type: 'integer')]
    private int $currentPage;

    #[OA\Property(property: 'data', description: '数据列表', type: 'array', items: new OA\Items(ref: UserExtendInfoResponse::class))]
    private array $data;

    #[OA\Property(property: 'firstPageUrl', description: '首页URL', type: 'string')]
    private string $firstPageUrl;

    #[OA\Property(property: 'from', description: '当前页面上的开始位置', type: 'integer')]
    private int $from;

    #[OA\Property(property: 'lastPage', description: '最后页码', type: 'integer')]
    private int $lastPage;

    #[OA\Property(property: 'lastPageUrl', description: '最后页URL', type: 'string')]
    private string $lastPageUrl;

    #[OA\Property(property: 'links', description: '分页链接的数组', type: 'array', items: new OA\Items(ref: PaginateLinkVo::class))]
    private array $links;

    #[OA\Property(property: 'nextPageUrl', description: '下一页URL', type: 'string')]
    private string $nextPageUrl;

    #[OA\Property(property: 'path', description: '分页URL', type: 'string')]
    private string $path;

    #[OA\Property(property: 'perPage', description: '每页显示的记录数量', type: 'integer')]
    private int $perPage;

    #[OA\Property(property: 'prevPageUrl', description: '上一页URL', type: 'string')]
    private string $prevPageUrl;

    #[OA\Property(property: 'to', description: '当前页面上的最后位置', type: 'integer')]
    private int $to;

    #[OA\Property(property: 'total', description: '数据总数', type: 'integer')]
    private int $total;

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getFirstPageUrl(): string
    {
        return $this->firstPageUrl;
    }

    public function setFirstPageUrl(string $firstPageUrl): void
    {
        $this->firstPageUrl = $firstPageUrl;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function setFrom(int $from): void
    {
        $this->from = $from;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function setLastPage(int $lastPage): void
    {
        $this->lastPage = $lastPage;
    }

    public function getLastPageUrl(): string
    {
        return $this->lastPageUrl;
    }

    public function setLastPageUrl(string $lastPageUrl): void
    {
        $this->lastPageUrl = $lastPageUrl;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function setLinks(array $links): void
    {
        $this->links = $links;
    }

    public function getNextPageUrl(): string
    {
        return $this->nextPageUrl;
    }

    public function setNextPageUrl(string $nextPageUrl): void
    {
        $this->nextPageUrl = $nextPageUrl;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    public function getPrevPageUrl(): string
    {
        return $this->prevPageUrl;
    }

    public function setPrevPageUrl(string $prevPageUrl): void
    {
        $this->prevPageUrl = $prevPageUrl;
    }

    public function getTo(): int
    {
        return $this->to;
    }

    public function setTo(int $to): void
    {
        $this->to = $to;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}
