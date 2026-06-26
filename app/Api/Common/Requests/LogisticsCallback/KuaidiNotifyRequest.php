<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\LogisticsCallback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonLogisticsKuaidiNotifyRequest',
    properties: [
        new OA\Property(property: 'com', description: '快递公司编码', type: 'string'),
        new OA\Property(property: 'num', description: '快递单号', type: 'string'),
        new OA\Property(property: 'status', description: '物流状态', type: 'string'),
        new OA\Property(property: 'data', description: '物流轨迹数据', type: 'array', items: new OA\Items(type: 'object')),
    ]
)]
class KuaidiNotifyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'com' => ['required', 'string'],
            'num' => ['required', 'string'],
            'status' => ['required', 'string'],
            'data' => ['required', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'com.required' => '快递公司编码不能为空',
            'num.required' => '快递单号不能为空',
            'status.required' => '物流状态不能为空',
            'data.required' => '物流轨迹数据不能为空',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
