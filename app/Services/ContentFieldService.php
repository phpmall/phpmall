<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentFieldRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class ContentFieldService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentFieldRepository
    {
        return ContentFieldRepository::getInstance();
    }
}
