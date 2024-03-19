<?php

declare(strict_types=1);

namespace App\Http\Requests\Region;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegionQueryRequest',
    required: [

    ],
    properties: [

    ]
)]
class RegionQueryRequest extends FormRequest
{
    protected array $rule = [

    ];

    protected array $message = [

    ];
}