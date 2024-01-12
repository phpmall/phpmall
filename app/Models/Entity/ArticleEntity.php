<?php

declare(strict_types=1);

namespace App\Models\Entity;

use Juling\Generator\Support\ArrayObject;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ArticleEntity')]
class ArticleEntity
{
    use ArrayObject;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    protected int $id;

    #[OA\Property(property: 'parent_id', description: '父级的ID', type: 'integer')]
    protected int $parentId;

    #[OA\Property(property: 'in_station', description: '内容类型:1站内,2站外', type: 'integer')]
    protected int $inStation;

    #[OA\Property(property: 'pattern_id', description: '模型ID', type: 'integer')]
    protected int $patternId;

    #[OA\Property(property: 'pattern_code', description: '模型类型', type: 'string')]
    protected string $patternCode;

    #[OA\Property(property: 'slug', description: 'URL PathInfo', type: 'string')]
    protected string $slug;

    #[OA\Property(property: 'title', description: '标题', type: 'string')]
    protected string $title;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    protected string $keywords;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    protected string $description;

    #[OA\Property(property: 'author', description: '编辑人员', type: 'string')]
    protected string $author;

    #[OA\Property(property: 'image', description: '图片', type: 'string')]
    protected string $image;

    #[OA\Property(property: 'intro', description: '简介', type: 'string')]
    protected string $intro;

    #[OA\Property(property: 'content', description: '描述', type: 'string')]
    protected string $content;

    #[OA\Property(property: 'extension', description: 'JSON内容扩展', type: 'string')]
    protected string $extension;

    #[OA\Property(property: 'attachment', description: '附件', type: 'string')]
    protected string $attachment;

    #[OA\Property(property: 'redirect_url', description: '站外链接', type: 'string')]
    protected string $redirectUrl;

    #[OA\Property(property: 'template_index', description: '频道模板', type: 'string')]
    protected string $templateIndex;

    #[OA\Property(property: 'template_list', description: '列表模板', type: 'string')]
    protected string $templateList;

    #[OA\Property(property: 'template_detail', description: '详情模板', type: 'string')]
    protected string $templateDetail;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    protected int $sort;

    #[OA\Property(property: 'status', description: '状态:1正常,2禁用', type: 'integer')]
    protected int $status;

    #[OA\Property(property: 'created_at', description: '', type: 'string')]
    protected string $createdAt;

    #[OA\Property(property: 'updated_at', description: '', type: 'string')]
    protected string $updatedAt;

    #[OA\Property(property: 'deleted_at', description: '', type: 'string')]
    protected string $deletedAt;

    /**
     * 获取
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取父级的ID
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级的ID
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取内容类型:1站内,2站外
     */
    public function getInStation(): int
    {
        return $this->inStation;
    }

    /**
     * 设置内容类型:1站内,2站外
     */
    public function setInStation(int $inStation): void
    {
        $this->inStation = $inStation;
    }

    /**
     * 获取模型ID
     */
    public function getPatternId(): int
    {
        return $this->patternId;
    }

    /**
     * 设置模型ID
     */
    public function setPatternId(int $patternId): void
    {
        $this->patternId = $patternId;
    }

    /**
     * 获取模型类型
     */
    public function getPatternCode(): string
    {
        return $this->patternCode;
    }

    /**
     * 设置模型类型
     */
    public function setPatternCode(string $patternCode): void
    {
        $this->patternCode = $patternCode;
    }

    /**
     * 获取URL PathInfo
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置URL PathInfo
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * 获取描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取编辑人员
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * 设置编辑人员
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * 获取图片
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * 设置图片
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * 获取简介
     */
    public function getIntro(): string
    {
        return $this->intro;
    }

    /**
     * 设置简介
     */
    public function setIntro(string $intro): void
    {
        $this->intro = $intro;
    }

    /**
     * 获取描述
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置描述
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取JSON内容扩展
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * 设置JSON内容扩展
     */
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * 获取附件
     */
    public function getAttachment(): string
    {
        return $this->attachment;
    }

    /**
     * 设置附件
     */
    public function setAttachment(string $attachment): void
    {
        $this->attachment = $attachment;
    }

    /**
     * 获取站外链接
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * 设置站外链接
     */
    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * 获取频道模板
     */
    public function getTemplateIndex(): string
    {
        return $this->templateIndex;
    }

    /**
     * 设置频道模板
     */
    public function setTemplateIndex(string $templateIndex): void
    {
        $this->templateIndex = $templateIndex;
    }

    /**
     * 获取列表模板
     */
    public function getTemplateList(): string
    {
        return $this->templateList;
    }

    /**
     * 设置列表模板
     */
    public function setTemplateList(string $templateList): void
    {
        $this->templateList = $templateList;
    }

    /**
     * 获取详情模板
     */
    public function getTemplateDetail(): string
    {
        return $this->templateDetail;
    }

    /**
     * 设置详情模板
     */
    public function setTemplateDetail(string $templateDetail): void
    {
        $this->templateDetail = $templateDetail;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:1正常,2禁用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1正常,2禁用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 获取
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
