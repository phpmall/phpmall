<?php

declare(strict_types=1);

namespace App\Bundles\User\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserBookingEntity')]
class UserBookingEntity
{
    use DTOHelper;

    const string getRecId = 'rec_id';

    const string getUserId = 'user_id'; // 用户ID

    const string getEmail = 'email'; // 邮箱

    const string getLinkMan = 'link_man'; // 联系人

    const string getTel = 'tel'; // 电话

    const string getGoodsId = 'goods_id'; // 商品ID

    const string getGoodsDesc = 'goods_desc'; // 商品描述

    const string getGoodsNumber = 'goods_number'; // 商品数量

    const string getBookingTime = 'booking_time'; // 预定时间

    const string getIsDispose = 'is_dispose'; // 是否处理

    const string getDisposeUser = 'dispose_user'; // 处理用户

    const string getDisposeTime = 'dispose_time'; // 处理时间

    const string getDisposeNote = 'dispose_note'; // 处理备注

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'recId', description: '', type: 'integer')]
    private int $recId;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'linkMan', description: '联系人', type: 'string')]
    private string $linkMan;

    #[OA\Property(property: 'tel', description: '电话', type: 'string')]
    private string $tel;

    #[OA\Property(property: 'goodsId', description: '商品ID', type: 'integer')]
    private int $goodsId;

    #[OA\Property(property: 'goodsDesc', description: '商品描述', type: 'string')]
    private string $goodsDesc;

    #[OA\Property(property: 'goodsNumber', description: '商品数量', type: 'integer')]
    private int $goodsNumber;

    #[OA\Property(property: 'bookingTime', description: '预定时间', type: 'integer')]
    private int $bookingTime;

    #[OA\Property(property: 'isDispose', description: '是否处理', type: 'integer')]
    private int $isDispose;

    #[OA\Property(property: 'disposeUser', description: '处理用户', type: 'string')]
    private string $disposeUser;

    #[OA\Property(property: 'disposeTime', description: '处理时间', type: 'integer')]
    private int $disposeTime;

    #[OA\Property(property: 'disposeNote', description: '处理备注', type: 'string')]
    private string $disposeNote;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getRecId(): int
    {
        return $this->recId;
    }

    /**
     * 设置
     */
    public function setRecId(int $recId): void
    {
        $this->recId = $recId;
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
     * 获取邮箱
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取联系人
     */
    public function getLinkMan(): string
    {
        return $this->linkMan;
    }

    /**
     * 设置联系人
     */
    public function setLinkMan(string $linkMan): void
    {
        $this->linkMan = $linkMan;
    }

    /**
     * 获取电话
     */
    public function getTel(): string
    {
        return $this->tel;
    }

    /**
     * 设置电话
     */
    public function setTel(string $tel): void
    {
        $this->tel = $tel;
    }

    /**
     * 获取商品ID
     */
    public function getGoodsId(): int
    {
        return $this->goodsId;
    }

    /**
     * 设置商品ID
     */
    public function setGoodsId(int $goodsId): void
    {
        $this->goodsId = $goodsId;
    }

    /**
     * 获取商品描述
     */
    public function getGoodsDesc(): string
    {
        return $this->goodsDesc;
    }

    /**
     * 设置商品描述
     */
    public function setGoodsDesc(string $goodsDesc): void
    {
        $this->goodsDesc = $goodsDesc;
    }

    /**
     * 获取商品数量
     */
    public function getGoodsNumber(): int
    {
        return $this->goodsNumber;
    }

    /**
     * 设置商品数量
     */
    public function setGoodsNumber(int $goodsNumber): void
    {
        $this->goodsNumber = $goodsNumber;
    }

    /**
     * 获取预定时间
     */
    public function getBookingTime(): int
    {
        return $this->bookingTime;
    }

    /**
     * 设置预定时间
     */
    public function setBookingTime(int $bookingTime): void
    {
        $this->bookingTime = $bookingTime;
    }

    /**
     * 获取是否处理
     */
    public function getIsDispose(): int
    {
        return $this->isDispose;
    }

    /**
     * 设置是否处理
     */
    public function setIsDispose(int $isDispose): void
    {
        $this->isDispose = $isDispose;
    }

    /**
     * 获取处理用户
     */
    public function getDisposeUser(): string
    {
        return $this->disposeUser;
    }

    /**
     * 设置处理用户
     */
    public function setDisposeUser(string $disposeUser): void
    {
        $this->disposeUser = $disposeUser;
    }

    /**
     * 获取处理时间
     */
    public function getDisposeTime(): int
    {
        return $this->disposeTime;
    }

    /**
     * 设置处理时间
     */
    public function setDisposeTime(int $disposeTime): void
    {
        $this->disposeTime = $disposeTime;
    }

    /**
     * 获取处理备注
     */
    public function getDisposeNote(): string
    {
        return $this->disposeNote;
    }

    /**
     * 设置处理备注
     */
    public function setDisposeNote(string $disposeNote): void
    {
        $this->disposeNote = $disposeNote;
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
