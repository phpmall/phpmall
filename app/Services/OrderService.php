<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OrderRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class OrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderRepository
    {
        return OrderRepository::getInstance();
    }
}
