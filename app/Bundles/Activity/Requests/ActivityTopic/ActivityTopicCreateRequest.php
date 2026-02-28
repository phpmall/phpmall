<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Requests\ActivityTopic;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ActivityTopicCreateRequest',
    required: [
        self::getTopicId,
        self::getTitle,
        self::getIntro,
        self::getStartTime,
        self::getEndTime,
        self::getData,
        self::getTemplate,
        self::getCss,
        self::getTopicImg,
        self::getTitlePic,
        self::getBaseStyle,
        self::getHtmls,
        self::getKeywords,
        self::getDescription,
        self::getCreatedTime,
        self::getUpdatedTime,
    ],
    properties: [
        new OA\Property(property: self::getTopicId, description: '专题ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '标题', type: 'string'),
        new OA\Property(property: self::getIntro, description: '简介', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'integer'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'integer'),
        new OA\Property(property: self::getData, description: '数据', type: 'string'),
        new OA\Property(property: self::getTemplate, description: '模板', type: 'string'),
        new OA\Property(property: self::getCss, description: 'CSS样式', type: 'string'),
        new OA\Property(property: self::getTopicImg, description: '主题图片', type: 'string'),
        new OA\Property(property: self::getTitlePic, description: '标题图片', type: 'string'),
        new OA\Property(property: self::getBaseStyle, description: '基础样式', type: 'string'),
        new OA\Property(property: self::getHtmls, description: 'HTML内容', type: 'string'),
        new OA\Property(property: self::getKeywords, description: '关键词', type: 'string'),
        new OA\Property(property: self::getDescription, description: '描述', type: 'string'),
        new OA\Property(property: self::getCreatedTime, description: '创建时间', type: 'string'),
        new OA\Property(property: self::getUpdatedTime, description: '更新时间', type: 'string'),
    ]
)]
class ActivityTopicCreateRequest extends FormRequest
{
    const string getTopicId = 'topicId';

    const string getTitle = 'title';

    const string getIntro = 'intro';

    const string getStartTime = 'startTime';

    const string getEndTime = 'endTime';

    const string getData = 'data';

    const string getTemplate = 'template';

    const string getCss = 'css';

    const string getTopicImg = 'topicImg';

    const string getTitlePic = 'titlePic';

    const string getBaseStyle = 'baseStyle';

    const string getHtmls = 'htmls';

    const string getKeywords = 'keywords';

    const string getDescription = 'description';

    const string getCreatedTime = 'createdTime';

    const string getUpdatedTime = 'updatedTime';

    public function rules(): array
    {
        return [
            self::getTopicId => 'required',
            self::getTitle => 'required',
            self::getIntro => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getData => 'required',
            self::getTemplate => 'required',
            self::getCss => 'required',
            self::getTopicImg => 'required',
            self::getTitlePic => 'required',
            self::getBaseStyle => 'required',
            self::getHtmls => 'required',
            self::getKeywords => 'required',
            self::getDescription => 'required',
            self::getCreatedTime => 'required',
            self::getUpdatedTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTopicId.'.required' => '请设置专题ID',
            self::getTitle.'.required' => '请设置标题',
            self::getIntro.'.required' => '请设置简介',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getData.'.required' => '请设置数据',
            self::getTemplate.'.required' => '请设置模板',
            self::getCss.'.required' => '请设置CSS样式',
            self::getTopicImg.'.required' => '请设置主题图片',
            self::getTitlePic.'.required' => '请设置标题图片',
            self::getBaseStyle.'.required' => '请设置基础样式',
            self::getHtmls.'.required' => '请设置HTML内容',
            self::getKeywords.'.required' => '请设置关键词',
            self::getDescription.'.required' => '请设置描述',
            self::getCreatedTime.'.required' => '请设置创建时间',
            self::getUpdatedTime.'.required' => '请设置更新时间',
        ];
    }
}
