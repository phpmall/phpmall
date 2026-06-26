<?php

declare(strict_types=1);

namespace App\Api\Shop\Responses\FreightTemplate;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopFreightTemplateCalculateResponse')]
class FreightTemplateCalculateResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'freight', description: '运费金额(分)', type: 'integer')]
    private int $freight;

    #[OA\Property(property: 'template_id', description: '运费模板ID', type: 'integer')]
    private int $templateId;

    #[OA\Property(property: 'template_name', description: '运费模板名称', type: 'string')]
    private string $templateName;

    #[OA\Property(property: 'region_name', description: '收货地区名称', type: 'string')]
    private string $regionName;

    #[OA\Property(property: 'calculation_type', description: '计费方式:1按重量,2按件数', type: 'integer')]
    private int $calculationType;

    public function getFreight(): int
    {
        return $this->freight;
    }

    public function setFreight(int $freight): void
    {
        $this->freight = $freight;
    }

    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    public function setTemplateId(int $templateId): void
    {
        $this->templateId = $templateId;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function setTemplateName(string $templateName): void
    {
        $this->templateName = $templateName;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    public function setRegionName(string $regionName): void
    {
        $this->regionName = $regionName;
    }

    public function getCalculationType(): int
    {
        return $this->calculationType;
    }

    public function setCalculationType(int $calculationType): void
    {
        $this->calculationType = $calculationType;
    }
}
