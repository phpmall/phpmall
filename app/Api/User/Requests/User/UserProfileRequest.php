<?php

declare(strict_types=1);

namespace App\Api\User\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserProfileRequest',
    description: '获取会员资料请求',
)]
class UserProfileRequest extends FormRequest
{
    public const string getWithAddresses = 'with_addresses';

    public function rules(): array
    {
        return [
            self::getWithAddresses => 'sometimes|nullable|integer|in:0,1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            self::getWithAddresses . '.integer' => '是否包含地址必须是整数',
            self::getWithAddresses . '.in' => '是否包含地址只能是 0 或 1',
        ];
    }
}
