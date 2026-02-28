<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FeedbackEntity')]
class FeedbackEntity
{
    use DTOHelper;

    const string getMsgId = 'msg_id';

    const string getParentId = 'parent_id'; // 父级ID

    const string getUserId = 'user_id'; // 用户ID

    const string getUserName = 'user_name'; // 用户名

    const string getUserEmail = 'user_email'; // 用户邮箱

    const string getMsgTitle = 'msg_title'; // 留言标题

    const string getMsgType = 'msg_type'; // 留言类型

    const string getMsgStatus = 'msg_status'; // 留言状态

    const string getMsgContent = 'msg_content'; // 留言内容

    const string getMsgTime = 'msg_time'; // 留言时间

    const string getMessageImg = 'message_img'; // 留言图片

    const string getOrderId = 'order_id'; // 订单ID

    const string getMsgArea = 'msg_area'; // 留言区域

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'msgId', description: '', type: 'integer')]
    private int $msgId;

    #[OA\Property(property: 'parentId', description: '父级ID', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'userName', description: '用户名', type: 'string')]
    private string $userName;

    #[OA\Property(property: 'userEmail', description: '用户邮箱', type: 'string')]
    private string $userEmail;

    #[OA\Property(property: 'msgTitle', description: '留言标题', type: 'string')]
    private string $msgTitle;

    #[OA\Property(property: 'msgType', description: '留言类型', type: 'integer')]
    private int $msgType;

    #[OA\Property(property: 'msgStatus', description: '留言状态', type: 'integer')]
    private int $msgStatus;

    #[OA\Property(property: 'msgContent', description: '留言内容', type: 'string')]
    private string $msgContent;

    #[OA\Property(property: 'msgTime', description: '留言时间', type: 'integer')]
    private int $msgTime;

    #[OA\Property(property: 'messageImg', description: '留言图片', type: 'string')]
    private string $messageImg;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'msgArea', description: '留言区域', type: 'integer')]
    private int $msgArea;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getMsgId(): int
    {
        return $this->msgId;
    }

    /**
     * 设置
     */
    public function setMsgId(int $msgId): void
    {
        $this->msgId = $msgId;
    }

    /**
     * 获取父级ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取用户名
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * 设置用户名
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * 获取用户邮箱
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * 设置用户邮箱
     */
    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /**
     * 获取留言标题
     */
    public function getMsgTitle(): string
    {
        return $this->msgTitle;
    }

    /**
     * 设置留言标题
     */
    public function setMsgTitle(string $msgTitle): void
    {
        $this->msgTitle = $msgTitle;
    }

    /**
     * 获取留言类型
     */
    public function getMsgType(): int
    {
        return $this->msgType;
    }

    /**
     * 设置留言类型
     */
    public function setMsgType(int $msgType): void
    {
        $this->msgType = $msgType;
    }

    /**
     * 获取留言状态
     */
    public function getMsgStatus(): int
    {
        return $this->msgStatus;
    }

    /**
     * 设置留言状态
     */
    public function setMsgStatus(int $msgStatus): void
    {
        $this->msgStatus = $msgStatus;
    }

    /**
     * 获取留言内容
     */
    public function getMsgContent(): string
    {
        return $this->msgContent;
    }

    /**
     * 设置留言内容
     */
    public function setMsgContent(string $msgContent): void
    {
        $this->msgContent = $msgContent;
    }

    /**
     * 获取留言时间
     */
    public function getMsgTime(): int
    {
        return $this->msgTime;
    }

    /**
     * 设置留言时间
     */
    public function setMsgTime(int $msgTime): void
    {
        $this->msgTime = $msgTime;
    }

    /**
     * 获取留言图片
     */
    public function getMessageImg(): string
    {
        return $this->messageImg;
    }

    /**
     * 设置留言图片
     */
    public function setMessageImg(string $messageImg): void
    {
        $this->messageImg = $messageImg;
    }

    /**
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取留言区域
     */
    public function getMsgArea(): int
    {
        return $this->msgArea;
    }

    /**
     * 设置留言区域
     */
    public function setMsgArea(int $msgArea): void
    {
        $this->msgArea = $msgArea;
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
