<?php

declare(strict_types=1);

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RoleQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class RoleQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}