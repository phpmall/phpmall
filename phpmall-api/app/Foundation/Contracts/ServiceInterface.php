<?php

declare(strict_types=1);

namespace App\Foundation\Contracts;

interface ServiceInterface
{
    public function getRepository(): CurdRepositoryInterface;
}
