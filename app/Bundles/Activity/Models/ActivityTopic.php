<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTopic extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_topic';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'topic_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'intro',
        'start_time',
        'end_time',
        'data',
        'template',
        'css',
        'topic_img',
        'title_pic',
        'base_style',
        'htmls',
        'keywords',
        'description',
        'created_time',
        'updated_time',
    ];
}
