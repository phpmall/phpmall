<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PayOrderRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class PayOrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): PayOrderRepository
    {
        return PayOrderRepository::getInstance();
    }
}
