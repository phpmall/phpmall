<?php

declare(strict_types=1);

namespace App\Foundation\Contracts;

use Illuminate\Database\Query\Builder;

interface RepositoryInterface
{
    public function model(): Builder;
}
