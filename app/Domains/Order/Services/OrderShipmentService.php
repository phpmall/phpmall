<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderShipmentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderShipmentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderShipmentRepository $repository,
    ) {}

    public function getRepository(): OrderShipmentRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
