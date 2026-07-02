<?php

declare(strict_types=1);

namespace App\Modules\Order\Services;

use App\Modules\Order\Repositories\OrderRefundRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderRefundService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderRefundRepository $repository,
    ) {}

    public function getRepository(): OrderRefundRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
