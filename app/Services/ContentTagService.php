<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentTagRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ContentTagService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRepository
    {
        return ContentTagRepository::getInstance();
    }
}
