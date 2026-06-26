<?php

declare(strict_types=1);

namespace App\Api\User\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ComplaintStoreRequest',
    required: [
        self::getType,
        self::getTargetType,
        self::getTargetId,
        self::getReason,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '投诉类型:product,service,logistics,other', type: 'string'),
        new OA\Property(property: self::getTargetType, description: '投诉对象类型:order,product,shop', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '投诉对象ID', type: 'integer'),
        new OA\Property(property: self::getReason, description: '投诉原因', type: 'string'),
        new OA\Property(property: self::getDescription, description: '详细描述', type: 'string', nullable: true),
        new OA\Property(
            property: self::getImages,
            description: '凭证图片',
            type: 'array',
            items: new OA\Items(type: 'string', format: 'uri'),
            nullable: true
        ),
        new OA\Property(property: self::getContact, description: '联系方式', type: 'string', nullable: true),
    ]
)]
class ComplaintStoreRequest extends FormRequest
{
    const string getType = 'type';

    const string getTargetType = 'target_type';

    const string getTargetId = 'target_id';

    const string getReason = 'reason';

    const string getDescription = 'description';

    const string getImages = 'images';

    const string getContact = 'contact';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:product,service,logistics,other'],
            self::getTargetType => ['required', 'string', 'in:order,product,shop'],
            self::getTargetId => ['required', 'integer', 'min:1'],
            self::getReason => ['required', 'string', 'max:500'],
            self::getDescription => ['nullable', 'string', 'max:2000'],
            self::getImages => ['nullable', 'array', 'max:9'],
            self::getImages.'.*' => ['string', 'url', 'max:500'],
            self::getContact => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请选择投诉类型',
            self::getTargetType.'.required' => '请选择投诉对象类型',
            self::getTargetId.'.required' => '请选择投诉对象',
            self::getReason.'.required' => '请填写投诉原因',
        ];
    }
}
