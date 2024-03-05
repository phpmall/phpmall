<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\ProductLadderRepository;

class ProductLadderService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductLadderRepository
    {
        return ProductLadderRepository::getInstance();
    }
}
