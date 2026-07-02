<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Services;

use App\Modules\Merchant\Repositories\MerchantRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MerchantService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MerchantRepository $repository,
    ) {}

    public function getRepository(): MerchantRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
