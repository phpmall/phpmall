<?php

declare(strict_types=1);

namespace App\Domains\Payment\Services;

use App\Domains\Payment\Repositories\PaymentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PaymentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PaymentRepository $repository,
    ) {}

    public function getRepository(): PaymentRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
