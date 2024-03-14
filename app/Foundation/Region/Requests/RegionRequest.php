<?php

declare(strict_types=1);

namespace App\Foundation\Region\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegionRequest',
    properties: [
        new OA\Property(property: 'id', description: '地区ID', type: 'integer'),
    ]
)]
class RegionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => '请提交上级地区PID参数',
            'id.integer' => '参数格式不符合',
        ];
    }
}
