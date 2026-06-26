<?php

declare(strict_types=1);

namespace App\Api\Seller\Requests\ShopDecoration;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'SellerShopDecorationUpdateRequest',
    required: [
        self::getTheme,
    ],
    properties: [
        new OA\Property(property: self::getTheme, description: '主题风格', type: 'string'),
        new OA\Property(property: self::getColorScheme, description: '配色方案', type: 'string', nullable: true),
        new OA\Property(property: self::getBannerImages, description: '轮播图列表', type: 'array', items: new OA\Items(type: 'string')),
        new OA\Property(property: self::getNavConfig, description: '导航配置(JSON)', type: 'string', nullable: true),
        new OA\Property(property: self::getCustomModules, description: '自定义模块(JSON)', type: 'string', nullable: true),
        new OA\Property(property: self::getIsEnabled, description: '是否启用:0否,1是', type: 'integer'),
    ]
)]
class ShopDecorationUpdateRequest extends FormRequest
{
    const string getTheme = 'theme';

    const string getColorScheme = 'color_scheme';

    const string getBannerImages = 'banner_images';

    const string getNavConfig = 'nav_config';

    const string getCustomModules = 'custom_modules';

    const string getIsEnabled = 'is_enabled';

    public function rules(): array
    {
        return [
            self::getTheme => ['required', 'string'],
            self::getColorScheme => ['nullable', 'string'],
            self::getBannerImages => ['nullable', 'array'],
            self::getBannerImages.'.*' => ['string'],
            self::getNavConfig => ['nullable', 'string'],
            self::getCustomModules => ['nullable', 'string'],
            self::getIsEnabled => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            self::getTheme.'.required' => '请选择主题风格',
            self::getIsEnabled.'.required' => '请选择启用状态',
            self::getIsEnabled.'.in' => '启用状态格式不正确',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
