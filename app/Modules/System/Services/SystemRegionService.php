<?php

declare(strict_types=1);

namespace App\Modules\System\Services;

use App\Modules\System\Repositories\SystemRegionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemRegionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemRegionRepository $repository,
    ) {}

    public function getRepository(): SystemRegionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
