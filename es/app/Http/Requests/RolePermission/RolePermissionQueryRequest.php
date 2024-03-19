<?php

declare(strict_types=1);

namespace App\Http\Requests\RolePermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RolePermissionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class RolePermissionQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}