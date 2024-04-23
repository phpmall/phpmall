<?php

declare(strict_types=1);

namespace App\Modules\Customer\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddressUpdateRequest',
    required: ['mobile'],
    properties: [
        new OA\Property(property: 'mobile', description: '手机号码', type: 'string', example: '13901889999'),
    ]
)]
class AddressUpdateRequest extends FormRequest
{
}
