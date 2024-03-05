<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\ContentTagRepository;

class ContentTagService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRepository
    {
        return ContentTagRepository::getInstance();
    }
}
