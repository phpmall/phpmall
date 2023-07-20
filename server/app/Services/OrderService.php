<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class OrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderRepository
    {
        return OrderRepository::getInstance();
    }
}
