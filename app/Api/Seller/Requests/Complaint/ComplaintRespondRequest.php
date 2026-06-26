<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerComplaintRespondRequest',
    required: [
        self::getContent,
    ],
    properties: [
        new OA\Property(property: self::getContent, description: '回应内容', type: 'string'),
        new OA\Property(property: self::getEvidence, description: '证据图片列表', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
    ]
)]
class ComplaintRespondRequest extends FormRequest
{
    const string getContent = 'content';

    const string getEvidence = 'evidence';

    public function rules(): array
    {
        return [
            self::getContent => 'required|string|max:2000',
            self::getEvidence => 'nullable|array',
            self::getEvidence.'.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            self::getContent.'.required' => '请填写回应内容',
            self::getContent.'.max' => '回应内容不能超过2000个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
