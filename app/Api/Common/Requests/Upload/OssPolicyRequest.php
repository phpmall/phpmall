<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Upload;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonUploadOssPolicyRequest',
    required: [
        self::getType,
    ],
    properties: [
        new OA\Property(property: self::getType, description: '文件类型: image,file', type: 'string'),
        new OA\Property(property: self::getFilename, description: '文件名', type: 'string', nullable: true),
    ]
)]
class OssPolicyRequest extends FormRequest
{
    const string getType = 'type';

    const string getFilename = 'filename';

    public function rules(): array
    {
        return [
            self::getType => ['required', 'string', 'in:image,file'],
            self::getFilename => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getType.'.required' => '请填写文件类型',
            self::getType.'.in' => '文件类型值不正确',
            self::getFilename.'.max' => '文件名不能超过255个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
