<?php

declare(strict_types=1);

namespace App\Api\Common\Requests\Upload;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CommonUploadImageRequest',
    required: [
        self::getFile,
    ],
    properties: [
        new OA\Property(property: self::getFile, description: '图片文件', type: 'string', format: 'binary'),
    ]
)]
class ImageRequest extends FormRequest
{
    const string getFile = 'file';

    public function rules(): array
    {
        return [
            self::getFile => ['required', 'image', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getFile.'.required' => '请上传图片文件',
            self::getFile.'.image' => '文件必须是图片格式',
            self::getFile.'.max' => '图片大小不能超过10MB',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
