<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductLadderRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ProductLadderService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductLadderRepository
    {
        return ProductLadderRepository::getInstance();
    }
}
