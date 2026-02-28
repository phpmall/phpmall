<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Services;

use App\Bundles\Goods\Repositories\GoodsArticleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class GoodsArticleBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): GoodsArticleRepository
    {
        return GoodsArticleRepository::getInstance();
    }
}
