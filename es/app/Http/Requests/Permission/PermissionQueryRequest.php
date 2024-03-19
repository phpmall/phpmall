<?php

declare(strict_types=1);

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PermissionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class PermissionQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}