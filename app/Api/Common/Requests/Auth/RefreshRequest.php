<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RefreshRequest',
    required: [],
    properties: []
)]
class RefreshRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return true;
    }
}
