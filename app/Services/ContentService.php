<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\ContentRepository;

class ContentService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentRepository
    {
        return ContentRepository::getInstance();
    }
}
