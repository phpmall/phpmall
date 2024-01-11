<?php

declare(strict_types=1);

namespace App\Api\Portal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

const UPLOAD_FILE_SIZE = 100 * 1024 * 1024; // 100MB

#[OA\Schema(
    schema: 'UploadRequest',
    required: ['file'],
    properties: [
        new OA\Property(property: 'file', description: '文件', type: 'file', format: 'binary'),
    ]
)]
class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|size:'.UPLOAD_FILE_SIZE,
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => '请上传文件',
        ];
    }
}
