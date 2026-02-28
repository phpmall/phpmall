<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Responses\ShopNav;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShopNavResponse')]
class ShopNavResponse
{
    use DTOHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '类型', type: 'string')]
    private string $type;

    #[OA\Property(property: 'ctype', description: '类别类型', type: 'string')]
    private string $ctype;

    #[OA\Property(property: 'cid', description: '类别ID', type: 'integer')]
    private int $cid;

    #[OA\Property(property: 'name', description: '导航名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'ifshow', description: '是否显示', type: 'integer')]
    private int $ifshow;

    #[OA\Property(property: 'vieworder', description: '显示顺序', type: 'integer')]
    private int $vieworder;

    #[OA\Property(property: 'opennew', description: '是否新窗口打开', type: 'integer')]
    private int $opennew;

    #[OA\Property(property: 'url', description: 'URL地址', type: 'string')]
    private string $url;

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
     * 获取类别类型
     */
    public function getCtype(): string
    {
        return $this->ctype;
    }

    /**
     * 设置类别类型
     */
    public function setCtype(string $ctype): void
    {
        $this->ctype = $ctype;
    }

    /**
     * 获取类别ID
     */
    public function getCid(): int
    {
        return $this->cid;
    }

    /**
     * 设置类别ID
     */
    public function setCid(int $cid): void
    {
        $this->cid = $cid;
    }

    /**
     * 获取导航名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置导航名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取是否显示
     */
    public function getIfshow(): int
    {
        return $this->ifshow;
    }

    /**
     * 设置是否显示
     */
    public function setIfshow(int $ifshow): void
    {
        $this->ifshow = $ifshow;
    }

    /**
     * 获取显示顺序
     */
    public function getVieworder(): int
    {
        return $this->vieworder;
    }

    /**
     * 设置显示顺序
     */
    public function setVieworder(int $vieworder): void
    {
        $this->vieworder = $vieworder;
    }

    /**
     * 获取是否新窗口打开
     */
    public function getOpennew(): int
    {
        return $this->opennew;
    }

    /**
     * 设置是否新窗口打开
     */
    public function setOpennew(int $opennew): void
    {
        $this->opennew = $opennew;
    }

    /**
     * 获取URL地址
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * 设置URL地址
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
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
