<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HomeRecommendTopicRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class HomeRecommendTopicService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeRecommendTopicRepository
    {
        return HomeRecommendTopicRepository::getInstance();
    }
}
