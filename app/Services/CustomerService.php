<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CustomerRepository;
use Juling\Generator\Contracts\ServiceInterface;
use Juling\Generator\Services\CommonService;

class CustomerService extends CommonService implements ServiceInterface
{
    public function getRepository(): CustomerRepository
    {
        return CustomerRepository::getInstance();
    }
}
