<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ServiceInterface;
use App\Repositories\ContentRelationRepository;

class ContentRelationService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentRelationRepository
    {
        return ContentRelationRepository::getInstance();
    }
}
