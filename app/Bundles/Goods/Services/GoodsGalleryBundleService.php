<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsGalleryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsGalleryBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsGalleryRepository
    {
        return GoodsGalleryRepository::getInstance();
    }
}
