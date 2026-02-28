<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Services;

use App\Bundles\Payment\Repositories\PaymentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PaymentBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): PaymentRepository
    {
        return PaymentRepository::getInstance();
    }
}
