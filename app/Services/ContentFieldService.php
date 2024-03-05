<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Services\CommonService;
use App\Repositories\ContentFieldRepository;

class ContentFieldService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentFieldRepository
    {
        return ContentFieldRepository::getInstance();
    }
}
