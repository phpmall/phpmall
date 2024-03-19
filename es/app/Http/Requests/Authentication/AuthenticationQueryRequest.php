<?php

declare(strict_types=1);

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthenticationQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class AuthenticationQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}