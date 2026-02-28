<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Entities;

use Juling\Foundation\Support\DTOHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ActivityTopicEntity')]
class ActivityTopicEntity
{
    use DTOHelper;

    const string getTopicId = 'topic_id'; // 专题ID

    const string getTitle = 'title'; // 标题

    const string getIntro = 'intro'; // 简介

    const string getStartTime = 'start_time'; // 开始时间

    const string getEndTime = 'end_time'; // 结束时间

    const string getData = 'data'; // 数据

    const string getTemplate = 'template'; // 模板

    const string getCss = 'css'; // CSS样式

    const string getTopicImg = 'topic_img'; // 主题图片

    const string getTitlePic = 'title_pic'; // 标题图片

    const string getBaseStyle = 'base_style'; // 基础样式

    const string getHtmls = 'htmls'; // HTML内容

    const string getKeywords = 'keywords'; // 关键词

    const string getDescription = 'description'; // 描述

    const string getCreatedTime = 'created_time'; // 创建时间

    const string getUpdatedTime = 'updated_time'; // 更新时间

    #[OA\Property(property: 'topicId', description: '专题ID', type: 'integer')]
    private int $topicId;

    #[OA\Property(property: 'title', description: '标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'intro', description: '简介', type: 'string')]
    private string $intro;

    #[OA\Property(property: 'startTime', description: '开始时间', type: 'integer')]
    private int $startTime;

    #[OA\Property(property: 'endTime', description: '结束时间', type: 'integer')]
    private int $endTime;

    #[OA\Property(property: 'data', description: '数据', type: 'string')]
    private string $data;

    #[OA\Property(property: 'template', description: '模板', type: 'string')]
    private string $template;

    #[OA\Property(property: 'css', description: 'CSS样式', type: 'string')]
    private string $css;

    #[OA\Property(property: 'topicImg', description: '主题图片', type: 'string')]
    private string $topicImg;

    #[OA\Property(property: 'titlePic', description: '标题图片', type: 'string')]
    private string $titlePic;

    #[OA\Property(property: 'baseStyle', description: '基础样式', type: 'string')]
    private string $baseStyle;

    #[OA\Property(property: 'htmls', description: 'HTML内容', type: 'string')]
    private string $htmls;

    #[OA\Property(property: 'keywords', description: '关键词', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'createdTime', description: '创建时间', type: 'string')]
    private string $createdTime;

    #[OA\Property(property: 'updatedTime', description: '更新时间', type: 'string')]
    private string $updatedTime;

    /**
     * 获取专题ID
     */
    public function getTopicId(): int
    {
        return $this->topicId;
    }

    /**
     * 设置专题ID
     */
    public function setTopicId(int $topicId): void
    {
        $this->topicId = $topicId;
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
     * 获取开始时间
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * 设置开始时间
     */
    public function setStartTime(int $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取结束时间
     */
    public function getEndTime(): int
    {
        return $this->endTime;
    }

    /**
     * 设置结束时间
     */
    public function setEndTime(int $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取数据
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * 设置数据
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * 获取模板
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * 设置模板
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * 获取CSS样式
     */
    public function getCss(): string
    {
        return $this->css;
    }

    /**
     * 设置CSS样式
     */
    public function setCss(string $css): void
    {
        $this->css = $css;
    }

    /**
     * 获取主题图片
     */
    public function getTopicImg(): string
    {
        return $this->topicImg;
    }

    /**
     * 设置主题图片
     */
    public function setTopicImg(string $topicImg): void
    {
        $this->topicImg = $topicImg;
    }

    /**
     * 获取标题图片
     */
    public function getTitlePic(): string
    {
        return $this->titlePic;
    }

    /**
     * 设置标题图片
     */
    public function setTitlePic(string $titlePic): void
    {
        $this->titlePic = $titlePic;
    }

    /**
     * 获取基础样式
     */
    public function getBaseStyle(): string
    {
        return $this->baseStyle;
    }

    /**
     * 设置基础样式
     */
    public function setBaseStyle(string $baseStyle): void
    {
        $this->baseStyle = $baseStyle;
    }

    /**
     * 获取HTML内容
     */
    public function getHtmls(): string
    {
        return $this->htmls;
    }

    /**
     * 设置HTML内容
     */
    public function setHtmls(string $htmls): void
    {
        $this->htmls = $htmls;
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
