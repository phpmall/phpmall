<?php

declare(strict_types=1);

namespace App\Bundles\Email\Responses\EmailTemplate;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'EmailTemplateResponse')]
class EmailTemplateResponse
{
    use DTOHelper;

    #[OA\Property(property: 'templateId', description: '', type: 'integer')]
    private int $templateId;

    #[OA\Property(property: 'type', description: '类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'templateCode', description: '模板代码', type: 'string')]
    private string $templateCode;

    #[OA\Property(property: 'isHtml', description: '是否HTML格式', type: 'integer')]
    private int $isHtml;

    #[OA\Property(property: 'templateSubject', description: '模板主题', type: 'string')]
    private string $templateSubject;

    #[OA\Property(property: 'templateContent', description: '模板内容', type: 'string')]
    private string $templateContent;

    #[OA\Property(property: 'lastModify', description: '最后修改时间', type: 'integer')]
    private int $lastModify;

    #[OA\Property(property: 'lastSend', description: '最后发送时间', type: 'integer')]
    private int $lastSend;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    /**
     * 设置
     */
    public function setTemplateId(int $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取类型
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取模板代码
     */
    public function getTemplateCode(): string
    {
        return $this->templateCode;
    }

    /**
     * 设置模板代码
     */
    public function setTemplateCode(string $templateCode): void
    {
        $this->templateCode = $templateCode;
    }

    /**
     * 获取是否HTML格式
     */
    public function getIsHtml(): int
    {
        return $this->isHtml;
    }

    /**
     * 设置是否HTML格式
     */
    public function setIsHtml(int $isHtml): void
    {
        $this->isHtml = $isHtml;
    }

    /**
     * 获取模板主题
     */
    public function getTemplateSubject(): string
    {
        return $this->templateSubject;
    }

    /**
     * 设置模板主题
     */
    public function setTemplateSubject(string $templateSubject): void
    {
        $this->templateSubject = $templateSubject;
    }

    /**
     * 获取模板内容
     */
    public function getTemplateContent(): string
    {
        return $this->templateContent;
    }

    /**
     * 设置模板内容
     */
    public function setTemplateContent(string $templateContent): void
    {
        $this->templateContent = $templateContent;
    }

    /**
     * 获取最后修改时间
     */
    public function getLastModify(): int
    {
        return $this->lastModify;
    }

    /**
     * 设置最后修改时间
     */
    public function setLastModify(int $lastModify): void
    {
        $this->lastModify = $lastModify;
    }

    /**
     * 获取最后发送时间
     */
    public function getLastSend(): int
    {
        return $this->lastSend;
    }

    /**
     * 设置最后发送时间
     */
    public function setLastSend(int $lastSend): void
    {
        $this->lastSend = $lastSend;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedTime(): string
    {
        return $this->createdTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedTime(string $createdTime): void
    {
        $this->createdTime = $createdTime;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedTime(): string
    {
        return $this->updatedTime;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedTime(string $updatedTime): void
    {
        $this->updatedTime = $updatedTime;
    }
}
