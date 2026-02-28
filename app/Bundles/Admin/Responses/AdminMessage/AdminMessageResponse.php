<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Responses\AdminMessage;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminMessageResponse')]
class AdminMessageResponse
{
    use DTOHelper;

    #[OA\Property(property: 'messageId', description: '', type: 'integer')]
    private int $messageId;

    #[OA\Property(property: 'senderId', description: '发送者ID', type: 'integer')]
    private int $senderId;

    #[OA\Property(property: 'receiverId', description: '接收者ID', type: 'integer')]
    private int $receiverId;

    #[OA\Property(property: 'sentTime', description: '发送时间', type: 'integer')]
    private int $sentTime;

    #[OA\Property(property: 'readTime', description: '阅读时间', type: 'integer')]
    private int $readTime;

    #[OA\Property(property: 'readed', description: '是否已读', type: 'integer')]
    private int $readed;

    #[OA\Property(property: 'deleted', description: '是否删除', type: 'integer')]
    private int $deleted;

    #[OA\Property(property: 'title', description: '消息标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'message', description: '消息内容', type: 'string')]
    private string $message;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * 设置
     */
    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * 获取发送者ID
     */
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * 设置发送者ID
     */
    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * 获取接收者ID
     */
    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    /**
     * 设置接收者ID
     */
    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    /**
     * 获取发送时间
     */
    public function getSentTime(): int
    {
        return $this->sentTime;
    }

    /**
     * 设置发送时间
     */
    public function setSentTime(int $sentTime): void
    {
        $this->sentTime = $sentTime;
    }

    /**
     * 获取阅读时间
     */
    public function getReadTime(): int
    {
        return $this->readTime;
    }

    /**
     * 设置阅读时间
     */
    public function setReadTime(int $readTime): void
    {
        $this->readTime = $readTime;
    }

    /**
     * 获取是否已读
     */
    public function getReaded(): int
    {
        return $this->readed;
    }

    /**
     * 设置是否已读
     */
    public function setReaded(int $readed): void
    {
        $this->readed = $readed;
    }

    /**
     * 获取是否删除
     */
    public function getDeleted(): int
    {
        return $this->deleted;
    }

    /**
     * 设置是否删除
     */
    public function setDeleted(int $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * 获取消息标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置消息标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取消息内容
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * 设置消息内容
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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
