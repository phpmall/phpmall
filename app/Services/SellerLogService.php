<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\SellerLogRepository;

class SellerLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerLogRepository
    {
        return SellerLogRepository::getInstance();
    }
}
