<?php

declare(strict_types=1);

namespace App\Bundles\Article\Services;

use App\Bundles\Article\Repositories\ArticleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ArticleBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): ArticleRepository
    {
        return ArticleRepository::getInstance();
    }
}
