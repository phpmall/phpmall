<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerLogRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class SellerLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerLogRepository
    {
        return SellerLogRepository::getInstance();
    }
}
