<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class InsertHelper
{
    /**
     * 获得查询次数以及查询时间
     *
     * @return string
     */
    public static function insert_query_info()
    {
        $queryLog = DB::getQueryLog();
        $queryCount = count($queryLog);
        $query_time = array_sum(array_column($queryLog, 'time')) / 1000;
        $query_time = number_format($query_time, 6);

        // 内存占用情况
        if (lang('memory_info') && function_exists('memory_get_usage')) {
            $memory_usage = sprintf(lang('memory_info'), memory_get_usage() / 1048576);
        } else {
            $memory_usage = '';
        }

        // 是否启用了 gzip
        $gzip_enabled = BaseHelper::gzip_enabled() ? lang('gzip_enabled') : lang('gzip_disabled');

        $online_count = DB::table('sessions')->count();

        // 加入触发cron代码
        $cron_method = empty(cfg('cron_method')) ? '<img src="api/cron.php?t='.TimeHelper::gmtime().'" alt="" style="width:0px;height:0px;" />' : '';

        return sprintf(lang('query_info'), $queryCount, $query_time, $online_count).$gzip_enabled.$memory_usage.$cron_method;
    }

    /**
     * 调用浏览历史
     *
     * @return string
     */
    public static function insert_history()
    {
        $str = '';
        $ecsHistory = Cookie::get('ECS');
        $history = is_array($ecsHistory) ? ($ecsHistory['history'] ?? '') : '';
        if (! empty($history)) {
            $res = DB::table('goods')
                ->select('goods_id', 'goods_name', 'goods_thumb', 'shop_price')
                ->whereIn('goods_id', (array) $history)
                ->where('is_on_sale', 1)
                ->where('is_alone_sale', 1)
                ->where('is_delete', 0)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            foreach ($res as $row) {
                $goods['goods_id'] = $row['goods_id'];
                $goods['goods_name'] = $row['goods_name'];
                $goods['short_name'] = (int) cfg('goods_name_length') > 0 ? Str::substr($row['goods_name'], (int) cfg('goods_name_length')) : $row['goods_name'];
                $goods['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
                $goods['shop_price'] = CommonHelper::price_format($row['shop_price']);
                $goods['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
                $str .= '<ul class="clearfix"><li class="goodsimg"><a href="'.$goods['url'].'" target="_blank"><img src="'.$goods['goods_thumb'].'" alt="'.$goods['goods_name'].'" class="B_blue" /></a></li><li><a href="'.$goods['url'].'" target="_blank" title="'.$goods['goods_name'].'">'.$goods['short_name'].'</a><br />'.lang('shop_price').'<font class="f1">'.$goods['shop_price'].'</font><br /></li></ul>';
            }
            $str .= '<ul id="clear_history"><a onclick="clear_history()">'.lang('clear_history').'</a></ul>';
        }

        return $str;
    }

    /**
     * 调用购物车信息
     *
     * @return string
     */
    public static function insert_cart_info()
    {
        $row = (array) DB::table('user_cart')
            ->select(DB::raw('SUM(goods_number) AS number'), DB::raw('SUM(goods_price * goods_number) AS amount'))
            ->where('session_id', SESS_ID)
            ->where('rec_type', CART_GENERAL_GOODS)
            ->first();

        if ($row) {
            $number = intval($row['number']);
            $amount = floatval($row['amount']);
        } else {
            $number = 0;
            $amount = 0;
        }

        $str = sprintf(lang('cart_info'), $number, CommonHelper::price_format($amount, false));

        return '<a href="flow.php" title="'.lang('view_cart').'">'.$str.'</a>';
    }

    /**
     * 调用指定的广告位的广告
     *
     * @param  int  $id  广告位ID
     * @param  int  $num  广告数量
     * @return string
     */
    public static function insert_ads($arr)
    {
        static $static_res = null;

        $arr['num'] = intval($arr['num']);
        $arr['id'] = intval($arr['id']);
        $time = TimeHelper::gmtime();
        if (! empty($arr['num']) && $arr['num'] != 1) {
            $res = DB::table('ad as a')
                ->select('a.ad_id', 'a.position_id', 'a.media_type', 'a.ad_link', 'a.ad_code', 'a.ad_name', 'p.ad_width', 'p.ad_height', 'p.position_style', DB::raw('RAND() AS rnd'))
                ->leftJoin('ad_position as p', 'a.position_id', '=', 'p.position_id')
                ->where('enabled', 1)
                ->where('start_time', '<=', $time)
                ->where('end_time', '>=', $time)
                ->where('a.position_id', (int) $arr['id'])
                ->orderBy('rnd')
                ->limit((int) $arr['num'])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        } else {
            if ($static_res[$arr['id']] === null) {
                $static_res[$arr['id']] = DB::table('ad as a')
                    ->select('a.ad_id', 'a.position_id', 'a.media_type', 'a.ad_link', 'a.ad_code', 'a.ad_name', 'p.ad_width', 'p.ad_height', 'p.position_style', DB::raw('RAND() AS rnd'))
                    ->leftJoin('ad_position as p', 'a.position_id', '=', 'p.position_id')
                    ->where('enabled', 1)
                    ->where('a.position_id', (int) $arr['id'])
                    ->where('start_time', '<=', $time)
                    ->where('end_time', '>=', $time)
                    ->orderBy('rnd')
                    ->limit(1)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
            }
            $res = $static_res[$arr['id']];
        }
        $ads = [];
        $position_style = '';

        foreach ($res as $row) {
            if ($row['position_id'] != $arr['id']) {
                continue;
            }
            $position_style = $row['position_style'];
            switch ($row['media_type']) {
                case 0: // 图片广告
                    $src = (strpos($row['ad_code'], 'http://') === false && strpos($row['ad_code'], 'https://') === false) ?
                        DATA_DIR."/afficheimg/$row[ad_code]" : $row['ad_code'];
                    $ads[] = "<a href='affiche.php?ad_id=$row[ad_id]&amp;uri=".urlencode($row['ad_link'])."'
                target='_blank'><img src='$src' width='".$row['ad_width']."' height='$row[ad_height]'
                border='0' /></a>";
                    break;
                case 1: // Flash
                    $src = (strpos($row['ad_code'], 'http://') === false && strpos($row['ad_code'], 'https://') === false) ?
                        DATA_DIR."/afficheimg/$row[ad_code]" : $row['ad_code'];
                    $ads[] = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '.
                        'codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"  '.
                        "width='$row[ad_width]' height='$row[ad_height]'>
                           <param name='movie' value='$src'>
                           <param name='quality' value='high'>
                           <embed src='$src' quality='high'
                           pluginspage='http://www.macromedia.com/go/getflashplayer'
                           type='application/x-shockwave-flash' width='$row[ad_width]'
                           height='$row[ad_height]'></embed>
                         </object>";
                    break;
                case 2: // CODE
                    $ads[] = $row['ad_code'];
                    break;
                case 3: // TEXT
                    $ads[] = "<a href='affiche.php?ad_id=$row[ad_id]&amp;uri=".urlencode($row['ad_link'])."'
                target='_blank'>".htmlspecialchars($row['ad_code']).'</a>';
                    break;
            }
        }
        $position_style = 'str:'.$position_style;

        $need_cache = tpl()->caching;
        tpl()->caching = false;

        tpl()->assign('ads', $ads);
        $val = tpl()->fetch($position_style);

        tpl()->caching = $need_cache;

        return $val;
    }

    /**
     * 调用会员信息
     *
     * @return string
     */
    public static function insert_member_info()
    {
        $need_cache = tpl()->caching;
        tpl()->caching = false;

        if (Session::get('user_id') > 0) {
            tpl()->assign('user_info', MainHelper::get_user_info());
        } else {
            $ecsUsername = Cookie::get('ECS');
            $username = is_array($ecsUsername) ? ($ecsUsername['username'] ?? '') : '';
            if (! empty($username)) {
                tpl()->assign('ecs_username', stripslashes($username));
            }
            $captcha = intval(cfg('captcha'));
            if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail') > 2)) && BaseHelper::gd_version() > 0) {
                tpl()->assign('enabled_captcha', 1);
                tpl()->assign('rand', mt_rand());
            }
        }
        $output = tpl()->fetch('web::library/member_info');

        tpl()->caching = $need_cache;

        return $output;
    }

    /**
     * 调用评论信息
     *
     * @return string
     */
    public static function insert_comments($arr)
    {
        $need_cache = tpl()->caching;
        $need_compile = tpl()->force_compile;

        tpl()->caching = false;
        tpl()->force_compile = true;
        $arr['id'] = intval($arr['id']);
        $arr['type'] = addslashes($arr['type']);

        // 验证码相关设置
        if ((intval(cfg('captcha')) & CAPTCHA_COMMENT) && BaseHelper::gd_version() > 0) {
            tpl()->assign('enabled_captcha', 1);
            tpl()->assign('rand', mt_rand());
        }
        tpl()->assign('username', stripslashes(Session::get('user_name')));
        tpl()->assign('email', Session::get('email'));
        tpl()->assign('comment_type', $arr['type']);
        tpl()->assign('id', $arr['id']);
        $cmt = MainHelper::assign_comment($arr['id'], $arr['type']);
        tpl()->assign('comments', $cmt['comments']);
        tpl()->assign('pager', $cmt['pager']);

        $val = tpl()->fetch('web::library/comments_list');

        tpl()->caching = $need_cache;
        tpl()->force_compile = $need_compile;

        return $val;
    }

    /**
     * 调用商品购买记录
     *
     * @return string
     */
    public static function insert_bought_notes($arr)
    {
        $need_cache = tpl()->caching;
        $need_compile = tpl()->force_compile;

        tpl()->caching = false;
        tpl()->force_compile = true;
        $arr['id'] = intval($arr['id']);

        // 商品购买记录
        $bought_notes = DB::table('order_info as oi')
            ->select('u.user_name', 'og.goods_number', 'oi.add_time', DB::raw('IF(oi.order_status IN (2, 3, 4), 0, 1) AS order_status'))
            ->leftJoin('user as u', 'oi.user_id', '=', 'u.user_id')
            ->join('order_goods as og', 'oi.order_id', '=', 'og.order_id')
            ->whereRaw('? - oi.add_time < 2592000', [TimeHelper::gmtime()])
            ->where('og.goods_id', (int) $arr['id'])
            ->orderByDesc('oi.add_time')
            ->limit(5)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($bought_notes as $key => $val) {
            $bought_notes[$key]['add_time'] = TimeHelper::local_date('Y-m-d G:i:s', $val['add_time']);
        }

        $count = DB::table('order_info as oi')
            ->join('order_goods as og', 'oi.order_id', '=', 'og.order_id')
            ->leftJoin('user as u', 'oi.user_id', '=', 'u.user_id')
            ->whereRaw('? - oi.add_time < 2592000', [TimeHelper::gmtime()])
            ->where('og.goods_id', (int) $arr['id'])
            ->count();

        // 商品购买记录分页样式
        $pager = [];
        $pager['page'] = $page = 1;
        $pager['size'] = $size = 5;
        $pager['record_count'] = $count;
        $pager['page_count'] = $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;
        $pager['page_first'] = "javascript:gotoBuyPage(1,$arr[id])";
        $pager['page_prev'] = $page > 1 ? 'javascript:gotoBuyPage('.($page - 1).",$arr[id])" : 'javascript:;';
        $pager['page_next'] = $page < $page_count ? 'javascript:gotoBuyPage('.($page + 1).",$arr[id])" : 'javascript:;';
        $pager['page_last'] = $page < $page_count ? 'javascript:gotoBuyPage('.$page_count.",$arr[id])" : 'javascript:;';

        tpl()->assign('notes', $bought_notes);
        tpl()->assign('pager', $pager);

        $val = tpl()->fetch('web::library/bought_notes');

        tpl()->caching = $need_cache;
        tpl()->force_compile = $need_compile;

        return $val;
    }

    /**
     * 调用在线调查信息
     *
     * @return string
     */
    public static function insert_vote()
    {
        $vote = MainHelper::get_vote();
        if (! empty($vote)) {
            tpl()->assign('vote_id', $vote['id']);
            tpl()->assign('vote', $vote['content']);
        }
        $val = tpl()->fetch('web::library/vote');

        return $val;
    }
}
