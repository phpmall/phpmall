<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StatRepository;
use Focite\Generator\Contracts\ServiceInterface;
use Focite\Generator\Services\CommonService;

class StatService extends CommonService implements ServiceInterface
{
    public function getRepository(): StatRepository
    {
        return StatRepository::getInstance();
    }
}
