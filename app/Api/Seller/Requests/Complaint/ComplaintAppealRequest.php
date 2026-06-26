<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerComplaintAppealRequest',
    required: [
        self::getReason,
    ],
    properties: [
        new OA\Property(property: self::getReason, description: '申诉理由', type: 'string'),
        new OA\Property(property: self::getEvidence, description: '证据图片列表', type: 'array', items: new OA\Items(type: 'string'), nullable: true),
    ]
)]
class ComplaintAppealRequest extends FormRequest
{
    const string getReason = 'reason';

    const string getEvidence = 'evidence';

    public function rules(): array
    {
        return [
            self::getReason => 'required|string|max:2000',
            self::getEvidence => 'nullable|array',
            self::getEvidence.'.*' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            self::getReason.'.required' => '请填写申诉理由',
            self::getReason.'.max' => '申诉理由不能超过2000个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
