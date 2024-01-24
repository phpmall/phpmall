<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentTagRelationRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class ContentTagRelationService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRelationRepository
    {
        return ContentTagRelationRepository::getInstance();
    }
}
