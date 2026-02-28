<?php

declare(strict_types=1);

namespace App\Bundles\Email\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'EmailSendEntity')]
class EmailSendEntity
{
    use DTOHelper;

    const string getId = 'id'; // ID

    const string getEmail = 'email'; // 邮箱地址

    const string getTemplateId = 'template_id'; // 模板ID

    const string getEmailContent = 'email_content'; // 邮件内容

    const string getError = 'error'; // 是否错误

    const string getPri = 'pri'; // 优先级

    const string getLastSend = 'last_send'; // 最后发送时间

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'email', description: '邮箱地址', type: 'string')]
    private string $email;

    #[OA\Property(property: 'templateId', description: '模板ID', type: 'integer')]
    private int $templateId;

    #[OA\Property(property: 'emailContent', description: '邮件内容', type: 'string')]
    private string $emailContent;

    #[OA\Property(property: 'error', description: '是否错误', type: 'integer')]
    private int $error;

    #[OA\Property(property: 'pri', description: '优先级', type: 'integer')]
    private int $pri;

    #[OA\Property(property: 'lastSend', description: '最后发送时间', type: 'integer')]
    private int $lastSend;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取邮箱地址
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱地址
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取模板ID
     */
    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    /**
     * 设置模板ID
     */
    public function setTemplateId(int $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取邮件内容
     */
    public function getEmailContent(): string
    {
        return $this->emailContent;
    }

    /**
     * 设置邮件内容
     */
    public function setEmailContent(string $emailContent): void
    {
        $this->emailContent = $emailContent;
    }

    /**
     * 获取是否错误
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * 设置是否错误
     */
    public function setError(int $error): void
    {
        $this->error = $error;
    }

    /**
     * 获取优先级
     */
    public function getPri(): int
    {
        return $this->pri;
    }

    /**
     * 设置优先级
     */
    public function setPri(int $pri): void
    {
        $this->pri = $pri;
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
