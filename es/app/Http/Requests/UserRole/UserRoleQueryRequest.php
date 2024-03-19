<?php

declare(strict_types=1);

namespace App\Http\Requests\UserRole;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserRoleQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserRoleQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}