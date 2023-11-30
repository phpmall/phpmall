<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PayOrderRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class PayOrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): PayOrderRepository
    {
        return PayOrderRepository::getInstance();
    }
}
