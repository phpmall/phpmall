<?php

declare(strict_types=1);

namespace App\Bundles\Article\Responses\Article;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ArticleResponse')]
class ArticleResponse
{
    use DTOHelper;

    #[OA\Property(property: 'articleId', description: '', type: 'integer')]
    private int $articleId;

    #[OA\Property(property: 'catId', description: '分类ID', type: 'integer')]
    private int $catId;

    #[OA\Property(property: 'title', description: '文章标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '文章内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'author', description: '作者', type: 'string')]
    private string $author;

    #[OA\Property(property: 'authorEmail', description: '作者邮箱', type: 'string')]
    private string $authorEmail;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'articleType', description: '文章类型', type: 'integer')]
    private int $articleType;

    #[OA\Property(property: 'isOpen', description: '是否公开', type: 'integer')]
    private int $isOpen;

    #[OA\Property(property: 'addTime', description: '添加时间', type: 'integer')]
    private int $addTime;

    #[OA\Property(property: 'fileUrl', description: '文件地址', type: 'string')]
    private string $fileUrl;

    #[OA\Property(property: 'openType', description: '打开方式', type: 'integer')]
    private int $openType;

    #[OA\Property(property: 'link', description: '链接地址', type: 'string')]
    private string $link;

    #[OA\Property(property: 'description', description: '文章描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * 设置
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * 获取分类ID
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * 设置分类ID
     */
    public function setCatId(int $catId): void
    {
        $this->catId = $catId;
    }

    /**
     * 获取文章标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置文章标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取文章内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置文章内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取作者
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * 设置作者
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * 获取作者邮箱
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    /**
     * 设置作者邮箱
     */
    public function setAuthorEmail(string $authorEmail): void
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * 获取关键词
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置关键词
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取文章类型
     */
    public function getArticleType(): int
    {
        return $this->articleType;
    }

    /**
     * 设置文章类型
     */
    public function setArticleType(int $articleType): void
    {
        $this->articleType = $articleType;
    }

    /**
     * 获取是否公开
     */
    public function getIsOpen(): int
    {
        return $this->isOpen;
    }

    /**
     * 设置是否公开
     */
    public function setIsOpen(int $isOpen): void
    {
        $this->isOpen = $isOpen;
    }

    /**
     * 获取添加时间
     */
    public function getAddTime(): int
    {
        return $this->addTime;
    }

    /**
     * 设置添加时间
     */
    public function setAddTime(int $addTime): void
    {
        $this->addTime = $addTime;
    }

    /**
     * 获取文件地址
     */
    public function getFileUrl(): string
    {
        return $this->fileUrl;
    }

    /**
     * 设置文件地址
     */
    public function setFileUrl(string $fileUrl): void
    {
        $this->fileUrl = $fileUrl;
    }

    /**
     * 获取打开方式
     */
    public function getOpenType(): int
    {
        return $this->openType;
    }

    /**
     * 设置打开方式
     */
    public function setOpenType(int $openType): void
    {
        $this->openType = $openType;
    }

    /**
     * 获取链接地址
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * 设置链接地址
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }

    /**
     * 获取文章描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置文章描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
