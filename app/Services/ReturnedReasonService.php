<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ReturnedReasonRepository;
use App\Contracts\ServiceInterface;
use App\Services\CommonService;

class ReturnedReasonService extends CommonService implements ServiceInterface
{
    public function getRepository(): ReturnedReasonRepository
    {
        return ReturnedReasonRepository::getInstance();
    }
}
