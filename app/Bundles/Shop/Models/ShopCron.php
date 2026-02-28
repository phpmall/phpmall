<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCron extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_cron';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'cron_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cron_code',
        'cron_name',
        'cron_desc',
        'cron_order',
        'cron_config',
        'thistime',
        'nextime',
        'day',
        'week',
        'hour',
        'minute',
        'enable',
        'run_once',
        'allow_ip',
        'alow_files',
        'created_time',
        'updated_time',
    ];
}
