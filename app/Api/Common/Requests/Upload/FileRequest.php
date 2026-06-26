<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Upload;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonUploadFileRequest',
    required: [
        self::getFile,
    ],
    properties: [
        new OA\Property(property: self::getFile, description: '文件', type: 'string', format: 'binary'),
    ]
)]
class FileRequest extends FormRequest
{
    const string getFile = 'file';

    public function rules(): array
    {
        return [
            self::getFile => ['required', 'file', 'max:51200'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getFile.'.required' => '请上传文件',
            self::getFile.'.file' => '文件格式不正确',
            self::getFile.'.max' => '文件大小不能超过50MB',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
