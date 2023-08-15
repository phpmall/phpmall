<?php

declare(strict_types=1);

namespace App\Contracts;

interface ServiceInterface
{
    public function getRepository(): CurdRepositoryInterface;
}
