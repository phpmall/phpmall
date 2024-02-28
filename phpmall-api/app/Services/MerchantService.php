<?php

declare(strict_types=1);

namespace App\Services;

use App\Foundation\Contracts\ServiceInterface;
use App\Foundation\Services\CommonService;
use App\Repositories\MerchantRepository;

class MerchantService extends CommonService implements ServiceInterface
{
    public function getRepository(): MerchantRepository
    {
        return MerchantRepository::getInstance();
    }
}
