<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeRecommendTopicRepository;
use Focite\Builder\Contracts\ServiceInterface;
use Focite\Builder\Services\CommonService;

class HomeRecommendTopicService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeRecommendTopicRepository
    {
        return HomeRecommendTopicRepository::getInstance();
    }
}
