<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\MerchantRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class MerchantService extends CommonService implements ServiceInterface
{
    public function getRepository(): MerchantRepository
    {
        return MerchantRepository::getInstance();
    }
}
