<?php

declare(strict_types=1);

namespace App\Bundles\Article\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArticleUpdateRequest',
    required: [
        self::getArticleId,
        self::getCatId,
        self::getTitle,
        self::getContent,
        self::getAuthor,
        self::getAuthorEmail,
        self::getKeywords,
        self::getArticleType,
        self::getIsOpen,
        self::getAddTime,
        self::getFileUrl,
        self::getOpenType,
        self::getLink,
        self::getDescription,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getArticleId, description: '', type: 'integer'),
        new OA\Property(property: self::getCatId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '文章标题', type: 'string'),
        new OA\Property(property: self::getContent, description: '文章内容', type: 'string'),
        new OA\Property(property: self::getAuthor, description: '作者', type: 'string'),
        new OA\Property(property: self::getAuthorEmail, description: '作者邮箱', type: 'string'),
        new OA\Property(property: self::getKeywords, description: '关键词', type: 'string'),
        new OA\Property(property: self::getArticleType, description: '文章类型', type: 'integer'),
        new OA\Property(property: self::getIsOpen, description: '是否公开', type: 'integer'),
        new OA\Property(property: self::getAddTime, description: '添加时间', type: 'integer'),
        new OA\Property(property: self::getFileUrl, description: '文件地址', type: 'string'),
        new OA\Property(property: self::getOpenType, description: '打开方式', type: 'integer'),
        new OA\Property(property: self::getLink, description: '链接地址', type: 'string'),
        new OA\Property(property: self::getDescription, description: '文章描述', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ArticleUpdateRequest extends FormRequest
{
    const string getArticleId = 'articleId';

    const string getCatId = 'catId';

    const string getTitle = 'title';

    const string getContent = 'content';

    const string getAuthor = 'author';

    const string getAuthorEmail = 'authorEmail';

    const string getKeywords = 'keywords';

    const string getArticleType = 'articleType';

    const string getIsOpen = 'isOpen';

    const string getAddTime = 'addTime';

    const string getFileUrl = 'fileUrl';

    const string getOpenType = 'openType';

    const string getLink = 'link';

    const string getDescription = 'description';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getArticleId => 'required',
            self::getCatId => 'required',
            self::getTitle => 'required',
            self::getContent => 'required',
            self::getAuthor => 'required',
            self::getAuthorEmail => 'required',
            self::getKeywords => 'required',
            self::getArticleType => 'required',
            self::getIsOpen => 'required',
            self::getAddTime => 'required',
            self::getFileUrl => 'required',
            self::getOpenType => 'required',
            self::getLink => 'required',
            self::getDescription => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getArticleId.'.required' => '请设置',
            self::getCatId.'.required' => '请设置分类ID',
            self::getTitle.'.required' => '请设置文章标题',
            self::getContent.'.required' => '请设置文章内容',
            self::getAuthor.'.required' => '请设置作者',
            self::getAuthorEmail.'.required' => '请设置作者邮箱',
            self::getKeywords.'.required' => '请设置关键词',
            self::getArticleType.'.required' => '请设置文章类型',
            self::getIsOpen.'.required' => '请设置是否公开',
            self::getAddTime.'.required' => '请设置添加时间',
            self::getFileUrl.'.required' => '请设置文件地址',
            self::getOpenType.'.required' => '请设置打开方式',
            self::getLink.'.required' => '请设置链接地址',
            self::getDescription.'.required' => '请设置文章描述',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
