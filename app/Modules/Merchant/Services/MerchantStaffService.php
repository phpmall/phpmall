<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Services;

use App\Modules\Merchant\Repositories\MerchantStaffRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MerchantStaffService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MerchantStaffRepository $repository,
    ) {}

    public function getRepository(): MerchantStaffRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
