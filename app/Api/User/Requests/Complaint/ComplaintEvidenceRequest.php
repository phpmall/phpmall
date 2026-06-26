<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ComplaintEvidenceRequest',
    required: [
        self::getEvidence,
    ],
    properties: [
        new OA\Property(
            property: self::getEvidence,
            description: '补充证据图片',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'uri')
        ),
        new OA\Property(property: self::getDescription, description: '补充说明', type: 'string', nullable: true),
    ]
)]
class ComplaintEvidenceRequest extends FormRequest
{
    const string getEvidence = 'evidence';

    const string getDescription = 'description';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getEvidence => ['required', 'array', 'min:1', 'max:9'],
            self::getEvidence.'.*' => ['string', 'url', 'max:500'],
            self::getDescription => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getEvidence.'.required' => '请上传证据图片',
        ];
    }
}
