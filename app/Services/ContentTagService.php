<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentTagRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ContentTagService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentTagRepository
    {
        return ContentTagRepository::getInstance();
    }
}
