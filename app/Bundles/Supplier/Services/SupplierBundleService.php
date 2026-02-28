<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Services;

use App\Bundles\Supplier\Repositories\SupplierRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SupplierBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): SupplierRepository
    {
        return SupplierRepository::getInstance();
    }
}
