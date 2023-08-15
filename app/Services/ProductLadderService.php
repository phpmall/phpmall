<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductLadderRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ProductLadderService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductLadderRepository
    {
        return ProductLadderRepository::getInstance();
    }
}
