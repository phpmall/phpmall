<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Repositories\PaymentRefundRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PaymentRefundService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PaymentRefundRepository $repository,
    ) {}

    public function getRepository(): PaymentRefundRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
