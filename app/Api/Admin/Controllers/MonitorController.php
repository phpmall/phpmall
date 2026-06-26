<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MonitorController extends Controller
{
    public function onlineUsers(): Response
    {
        $lifetime = (int) config('session.lifetime') * 60;
        $activeSince = now()->timestamp - $lifetime;

        $sessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select([
                'sessions.id',
                'sessions.user_id',
                'users.name',
                'users.email',
                'sessions.ip_address',
                'sessions.user_agent',
                'sessions.last_activity',
            ])
            ->where('sessions.last_activity', '>=', $activeSince)
            ->orderByDesc('sessions.last_activity')
            ->paginate(10);

        return Inertia::render('admin/monitor', [
            'title' => '在线用户',
            'description' => '直接读取 sessions 表展示当前仍在有效期内的用户会话。',
            'items' => $sessions,
            'columns' => [
                ['key' => 'name', 'label' => '用户'],
                ['key' => 'email', 'label' => '邮箱'],
                ['key' => 'ip_address', 'label' => 'IP 地址'],
                ['key' => 'user_agent', 'label' => '用户代理'],
                ['key' => 'last_activity', 'label' => '最后活动', 'type' => 'unix'],
            ],
        ]);
    }

    public function service(): Response
    {
        return Inertia::render('admin/status', [
            'title' => '服务监控',
            'description' => '实时读取 PHP 与运行环境状态，不默认落库。',
            'cards' => [
                ['label' => 'PHP 版本', 'value' => PHP_VERSION],
                ['label' => 'Laravel 版本', 'value' => app()->version()],
                ['label' => '运行环境', 'value' => app()->environment()],
                ['label' => '服务器时间', 'value' => now()->toDateTimeString()],
                ['label' => '时区', 'value' => config('app.timezone')],
                ['label' => '内存上限', 'value' => ini_get('memory_limit')],
            ],
        ]);
    }

    public function cache(): Response
    {
        $cards = [
            ['label' => '默认缓存驱动', 'value' => (string) config('cache.default')],
            ['label' => '缓存前缀', 'value' => (string) config('cache.prefix')],
        ];

        try {
            if (config('cache.default') === 'redis') {
                $info = Redis::connection()->command('info');
                $cards[] = ['label' => 'Redis 状态', 'value' => '可用'];
                $cards[] = ['label' => 'Redis 信息', 'value' => is_string($info) ? mb_substr($info, 0, 500) : '已读取'];
            } else {
                Cache::store()->get('admin-cache-healthcheck');
                $cards[] = ['label' => '缓存状态', 'value' => '可用'];
            }
        } catch (Throwable $e) {
            $cards[] = ['label' => '缓存状态', 'value' => '不可用：'.$e->getMessage()];
        }

        return Inertia::render('admin/status', [
            'title' => '缓存监控',
            'description' => '实时读取缓存驱动状态，Redis 可接入 INFO/DBSIZE 等指标。',
            'cards' => $cards,
        ]);
    }

    public function connectionPool(): Response
    {
        $cards = [
            ['label' => '默认数据库连接', 'value' => (string) config('database.default')],
        ];

        try {
            DB::connection()->getPdo();
            $cards[] = ['label' => '数据库连接', 'value' => '可用'];
            $cards[] = ['label' => '数据库驱动', 'value' => DB::connection()->getDriverName()];
        } catch (Throwable $e) {
            $cards[] = ['label' => '数据库连接', 'value' => '不可用：'.$e->getMessage()];
        }

        return Inertia::render('admin/status', [
            'title' => '连接池监视',
            'description' => '实时检测数据库连接状态；具体连接池指标可后续按驱动接入。',
            'cards' => $cards,
        ]);
    }
}
