<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\HomeRecommendTopicRepository;

class HomeRecommendTopicService extends CommonService implements ServiceInterface
{
    public function getRepository(): HomeRecommendTopicRepository
    {
        return HomeRecommendTopicRepository::getInstance();
    }
}
