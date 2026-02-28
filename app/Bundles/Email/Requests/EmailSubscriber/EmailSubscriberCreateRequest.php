<?php

declare(strict_types=1);

namespace App\Bundles\Email\Requests\EmailSubscriber;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'EmailSubscriberCreateRequest',
    required: [
        self::getEmail,
        self::getStat,
        self::getHash,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getEmail, description: '邮箱地址', type: 'string'),
        new OA\Property(property: self::getStat, description: '状态', type: 'integer'),
        new OA\Property(property: self::getHash, description: '哈希值', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class EmailSubscriberCreateRequest extends FormRequest
{
    const string getEmail = 'email';

    const string getStat = 'stat';

    const string getHash = 'hash';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getEmail => 'required',
            self::getStat => 'required',
            self::getHash => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getEmail.'.required' => '请设置邮箱地址',
            self::getStat.'.required' => '请设置状态',
            self::getHash.'.required' => '请设置哈希值',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
