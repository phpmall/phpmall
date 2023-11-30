<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentModelRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ContentModelService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentModelRepository
    {
        return ContentModelRepository::getInstance();
    }
}
