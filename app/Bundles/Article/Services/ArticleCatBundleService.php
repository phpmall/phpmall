<?php

declare(strict_types=1);

namespace App\Bundles\Article\Services;

use App\Bundles\Article\Repositories\ArticleCatRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ArticleCatBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ArticleCatRepository
    {
        return ArticleCatRepository::getInstance();
    }
}
