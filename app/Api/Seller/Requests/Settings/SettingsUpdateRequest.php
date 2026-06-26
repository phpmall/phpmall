<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerSettingsUpdateRequest',
    required: [
        self::getNotificationSettings,
        self::getLogisticsSettings,
        self::getRefundSettings,
        self::getOtherSettings,
    ],
    properties: [
        new OA\Property(property: self::getNotificationSettings, description: '通知设置', type: 'object'),
        new OA\Property(property: self::getLogisticsSettings, description: '物流设置', type: 'object'),
        new OA\Property(property: self::getRefundSettings, description: '退款设置', type: 'object'),
        new OA\Property(property: self::getOtherSettings, description: '其他设置', type: 'object'),
    ]
)]
class SettingsUpdateRequest extends FormRequest
{
    const string getNotificationSettings = 'notification_settings';

    const string getLogisticsSettings = 'logistics_settings';

    const string getRefundSettings = 'refund_settings';

    const string getOtherSettings = 'other_settings';

    public function rules(): array
    {
        return [
            self::getNotificationSettings => 'required|array',
            self::getLogisticsSettings => 'required|array',
            self::getRefundSettings => 'required|array',
            self::getOtherSettings => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            self::getNotificationSettings.'.required' => '请填写通知设置',
            self::getLogisticsSettings.'.required' => '请填写物流设置',
            self::getRefundSettings.'.required' => '请填写退款设置',
            self::getOtherSettings.'.required' => '请填写其他设置',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
