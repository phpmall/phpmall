<?php

declare(strict_types=1);

namespace App\Http\Requests\UserPermission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserPermissionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserPermissionQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}