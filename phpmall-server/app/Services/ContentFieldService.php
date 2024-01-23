<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentFieldRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ContentFieldService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentFieldRepository
    {
        return ContentFieldRepository::getInstance();
    }
}
