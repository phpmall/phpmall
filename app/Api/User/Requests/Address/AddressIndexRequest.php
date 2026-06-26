<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressIndexRequest',
)]
class AddressIndexRequest extends FormRequest
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
