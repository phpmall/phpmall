<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SellerRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class SellerService extends CommonService implements ServiceInterface
{
    public function getRepository(): SellerRepository
    {
        return SellerRepository::getInstance();
    }
}
