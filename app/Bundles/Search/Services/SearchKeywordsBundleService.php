<?php

declare(strict_types=1);

namespace App\Bundles\Search\Services;

use App\Bundles\Search\Repositories\SearchKeywordsRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SearchKeywordsBundleService extends CommonService implements ServiceInterface
{
    public function getRepository(): SearchKeywordsRepository
    {
        return SearchKeywordsRepository::getInstance();
    }
}
