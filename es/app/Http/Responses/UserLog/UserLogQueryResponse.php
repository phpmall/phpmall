<?php

declare(strict_types=1);

namespace App\Http\Responses\UserLog;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserLogQueryResponse')]
class UserLogQueryResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'total', description: '数据总数', type: 'integer')]
    private int $total;

    #[OA\Property(property: 'per_page', description: '每页数据量', type: 'integer')]
    private int $perPage;

    #[OA\Property(property: 'current_page', description: '当前页码', type: 'integer')]
    private int $currentPage;

    #[OA\Property(property: 'last_page', description: '最后页面', type: 'integer')]
    private int $lastPage;

    #[OA\Property(property: 'data', description: '数据列表', type: 'array', items: new OA\Items(ref: UserLogResponse::class))]
    private array $data;

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function setPerPage(int $perPage): void
    {
        $this->perPage = $perPage;
    }

    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    public function setLastPage(int $lastPage): void
    {
        $this->lastPage = $lastPage;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}