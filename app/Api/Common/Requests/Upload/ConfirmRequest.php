<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Upload;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonUploadConfirmRequest',
    required: [
        self::getPath,
    ],
    properties: [
        new OA\Property(property: self::getPath, description: '文件路径', type: 'string'),
        new OA\Property(property: self::getName, description: '原始文件名', type: 'string', nullable: true),
        new OA\Property(property: self::getSize, description: '文件大小(字节)', type: 'integer', nullable: true),
        new OA\Property(property: self::getMimeType, description: 'MIME类型', type: 'string', nullable: true),
    ]
)]
class ConfirmRequest extends FormRequest
{
    const string getPath = 'path';

    const string getName = 'name';

    const string getSize = 'size';

    const string getMimeType = 'mime_type';

    public function rules(): array
    {
        return [
            self::getPath => ['required', 'string', 'max:500'],
            self::getName => ['nullable', 'string', 'max:255'],
            self::getSize => ['nullable', 'integer', 'min:0'],
            self::getMimeType => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getPath.'.required' => '请填写文件路径',
            self::getPath.'.max' => '文件路径不能超过500个字符',
            self::getName.'.max' => '文件名不能超过255个字符',
            self::getSize.'.integer' => '文件大小必须是整数',
            self::getMimeType.'.max' => 'MIME类型不能超过100个字符',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
