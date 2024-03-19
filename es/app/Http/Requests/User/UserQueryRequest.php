<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class UserQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}