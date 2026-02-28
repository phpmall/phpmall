<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MainHelper
{
    /**
     * 更新用户SESSION,COOKIE及登录时间、登录次数。
     *
     * @return void
     */
    public static function update_user_info()
    {
        if (! Session::has('user_id')) {
            return false;
        }

        // 查询会员信息
        $time = date('Y-m-d');
        $row = (array) DB::table('user as u')
            ->select('u.user_money', 'u.email', 'u.pay_points', 'u.user_rank', 'u.rank_points', DB::raw('IFNULL(b.type_money, 0) AS user_bonus'), 'u.last_login', 'u.last_ip')
            ->leftJoin('user_bonus as ub', function ($join) {
                $join->on('ub.user_id', '=', 'u.user_id')
                    ->where('ub.used_time', '=', 0);
            })
            ->leftJoin('activity_bonus as b', function ($join) use ($time) {
                $join->on('b.type_id', '=', 'ub.bonus_type_id')
                    ->where('b.use_start_date', '<=', $time)
                    ->where('b.use_end_date', '>=', $time);
            })
            ->where('u.user_id', Session::get('user_id'))
            ->first();

        if ($row) {
            // 更新SESSION
            Session::put('last_time', $row['last_login']);
            Session::put('last_ip', $row['last_ip']);
            Session::put('login_fail', 0);
            Session::put('email', $row['email']);

            // 判断是否是特殊等级，可能后台把特殊会员组更改普通会员组
            if ($row['user_rank'] > 0) {
                $special_rank = DB::table('user_rank')->where('rank_id', $row['user_rank'])->value('special_rank');
                if ($special_rank === '0' || $special_rank === null) {
                    DB::table('user')->where('user_id', Session::get('user_id'))->update(['user_rank' => 0]);
                    $row['user_rank'] = 0;
                }
            }

            // 取得用户等级和折扣
            if ($row['user_rank'] === 0) {
                // 非特殊等级，根据等级积分计算用户等级（注意：不包括特殊等级）
                $rank = (array) DB::table('user_rank')
                    ->select('rank_id', 'discount')
                    ->where('special_rank', '0')
                    ->where('min_points', '<=', (int) $row['rank_points'])
                    ->where('max_points', '>', (int) $row['rank_points'])
                    ->first();
                if ($rank) {
                    Session::put('user_rank', $rank['rank_id']);
                    Session::put('discount', $rank['discount'] / 100.00);
                } else {
                    Session::put('user_rank', 0);
                    Session::put('discount', 1);
                }
            } else {
                // 特殊等级
                $rank = (array) DB::table('user_rank')
                    ->select('rank_id', 'discount')
                    ->where('rank_id', $row['user_rank'])
                    ->first();
                if ($rank) {
                    Session::put('user_rank', $rank['rank_id']);
                    Session::put('discount', $rank['discount'] / 100.00);
                } else {
                    Session::put('user_rank', 0);
                    Session::put('discount', 1);
                }
            }
        }

        // 更新登录时间，登录次数及登录ip
        DB::table('user')->where('user_id', Session::get('user_id'))->update([
            'visit_count' => DB::raw('visit_count + 1'),
            'last_ip' => BaseHelper::real_ip(),
            'last_login' => TimeHelper::gmtime(),
        ]);
    }

    /**
     *  获取用户信息数组
     *
     *
     * @return array $user       用户信息数组
     */
    public static function get_user_info($id = 0)
    {
        if ($id === 0) {
            $id = Session::get('user_id');
        }
        $user = (array) DB::table('user as u')
            ->select('u.user_id', 'u.email', 'u.user_name', 'u.user_money', 'u.pay_points')
            ->where('u.user_id', $id)
            ->first();
        $bonus = MainHelper::get_user_bonus($id);

        $user['username'] = $user['user_name'];
        $user['user_points'] = $user['pay_points'].cfg('integral_name');
        $user['user_money'] = CommonHelper::price_format($user['user_money'], false);
        $user['user_bonus'] = CommonHelper::price_format($bonus['bonus_value'], false);

        return $user;
    }

    /**
     * 获得指定分类的所有上级分类
     *
     * @param  int  $cat  分类编号
     * @return array
     */
    public static function get_parent_cats($cat)
    {
        if ($cat === 0) {
            return [];
        }

        $arr = DB::table('goods_category')
            ->select('cat_id', 'cat_name', 'parent_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (empty($arr)) {
            return [];
        }

        $index = 0;
        $cats = [];

        while (1) {
            foreach ($arr as $row) {
                if ($cat === $row['cat_id']) {
                    $cat = $row['parent_id'];

                    $cats[$index]['cat_id'] = $row['cat_id'];
                    $cats[$index]['cat_name'] = $row['cat_name'];

                    $index++;
                    break;
                }
            }

            if ($index === 0 || $cat === 0) {
                break;
            }
        }

        return $cats;
    }

    /**
     * 根据提供的数组编译成页面标题
     *
     * @param  string  $type  类型
     * @param  array  $arr  分类数组
     * @return string
     */
    public static function build_pagetitle($arr, $type = 'category')
    {
        $str = '';

        foreach ($arr as $val) {
            $str .= htmlspecialchars($val['cat_name']).'_';
        }

        return $str;
    }

    /**
     * 根据提供的数组编译成当前位置
     *
     * @param  string  $type  类型
     * @param  array  $arr  分类数组
     * @return void
     */
    public static function build_urhere($arr, $type = 'category')
    {
        krsort($arr);

        $str = '';
        foreach ($arr as $val) {
            switch ($type) {
                case 'category':
                case 'brand':
                    $args = ['cid' => $val['cat_id']];
                    break;
                case 'article_cat':
                    $args = ['acid' => $val['cat_id']];
                    break;
            }

            $str .= ' <code>&gt;</code> <a href="'.build_uri($type, $args).'">'.htmlspecialchars($val['cat_name']).'</a>';
        }

        return $str;
    }

    /**
     * 分配文章列表给smarty
     *
     * @param  int  $id  文章分类的编号
     * @param  int  $num  文章数量
     * @return array
     */
    public static function assign_articles($id, $num)
    {

        $cat['id'] = $id;
        $cat['name'] = DB::table('article_cat')->where('cat_id', $id)->value('cat_name');
        $cat['url'] = build_uri('article_cat', ['acid' => $id], $cat['name']);

        $articles['cat'] = $cat;
        $articles['arr'] = ArticleHelper::get_cat_articles($id, 1, $num);

        return $articles;
    }

    /**
     * 分配帮助信息
     *
     * @return array
     */
    public static function get_shop_help()
    {
        $res = DB::table('article as a')
            ->select('c.cat_id', 'c.cat_name', 'c.sort_order', 'a.article_id', 'a.title', 'a.file_url', 'a.open_type')
            ->leftJoin('article_cat as c', 'a.cat_id', '=', 'c.cat_id')
            ->where('c.cat_type', 5)
            ->where('a.is_open', 1)
            ->orderBy('c.sort_order')
            ->orderBy('a.article_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $key => $row) {
            $arr[$row['cat_id']]['cat_id'] = build_uri('article_cat', ['acid' => $row['cat_id']], $row['cat_name']);
            $arr[$row['cat_id']]['cat_name'] = $row['cat_name'];
            $arr[$row['cat_id']]['article'][$key]['article_id'] = $row['article_id'];
            $arr[$row['cat_id']]['article'][$key]['title'] = $row['title'];
            $arr[$row['cat_id']]['article'][$key]['short_title'] = (int) cfg('article_title_length') > 0 ?
                Str::substr($row['title'], (int) cfg('article_title_length')) : $row['title'];
            $arr[$row['cat_id']]['article'][$key]['url'] = $row['open_type'] != 1 ?
                build_uri('article', ['aid' => $row['article_id']], $row['title']) : trim($row['file_url']);
        }

        return $arr;
    }

    /**
     * 创建分页信息
     *
     * @param  string  $app  程序名称，如category
     * @param  string  $cat  分类ID
     * @param  string  $record_count  记录总数
     * @param  string  $size  每页记录数
     * @param  string  $sort  排序类型
     * @param  string  $order  排序顺序
     * @param  string  $page  当前页
     * @param  string  $keywords  查询关键字
     * @param  string  $brand  品牌
     * @param  string  $price_min  最小价格
     * @param  string  $price_max  最高价格
     * @return void
     */
    public static function assign_pager(
        $app,
        $cat,
        $record_count,
        $size,
        $sort,
        $order,
        $page = 1,
        $keywords = '',
        $brand = 0,
        $price_min = 0,
        $price_max = 0,
        $display_type = 'list',
        $filter_attr = '',
        $url_format = '',
        $sch_array = ''
    ) {
        $sch = [
            'keywords' => $keywords,
            'sort' => $sort,
            'order' => $order,
            'cat' => $cat,
            'brand' => $brand,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'filter_attr' => $filter_attr,
            'display' => $display_type,
        ];

        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }

        $page_count = $record_count > 0 ? intval(ceil($record_count / $size)) : 1;

        $pager['page'] = $page;
        $pager['size'] = $size;
        $pager['sort'] = $sort;
        $pager['order'] = $order;
        $pager['record_count'] = $record_count;
        $pager['page_count'] = $page_count;
        $pager['display'] = $display_type;

        switch ($app) {
            case 'category':
                $uri_args = ['cid' => $cat, 'bid' => $brand, 'price_min' => $price_min, 'price_max' => $price_max, 'filter_attr' => $filter_attr, 'sort' => $sort, 'order' => $order, 'display' => $display_type];
                break;
            case 'article_cat':
                $uri_args = ['acid' => $cat, 'sort' => $sort, 'order' => $order];
                break;
            case 'brand':
                $uri_args = ['cid' => $cat, 'bid' => $brand, 'sort' => $sort, 'order' => $order, 'display' => $display_type];
                break;
            case 'search':
                $uri_args = ['cid' => $cat, 'bid' => $brand, 'sort' => $sort, 'order' => $order];
                break;
            case 'exchange':
                $uri_args = ['cid' => $cat, 'integral_min' => $price_min, 'integral_max' => $price_max, 'sort' => $sort, 'order' => $order, 'display' => $display_type];
                break;
        }
        // 分页样式
        $pager['styleid'] = cfg('page_style') ? intval(cfg('page_style')) : 0;

        $page_prev = ($page > 1) ? $page - 1 : 1;
        $page_next = ($page < $page_count) ? $page + 1 : $page_count;
        if ($pager['styleid'] === 0) {
            if (! empty($url_format)) {
                $pager['page_first'] = $url_format. 1;
                $pager['page_prev'] = $url_format.$page_prev;
                $pager['page_next'] = $url_format.$page_next;
                $pager['page_last'] = $url_format.$page_count;
            } else {
                $pager['page_first'] = build_uri($app, $uri_args, '', 1, $keywords);
                $pager['page_prev'] = build_uri($app, $uri_args, '', $page_prev, $keywords);
                $pager['page_next'] = build_uri($app, $uri_args, '', $page_next, $keywords);
                $pager['page_last'] = build_uri($app, $uri_args, '', $page_count, $keywords);
            }
            $pager['array'] = [];

            for ($i = 1; $i <= $page_count; $i++) {
                $pager['array'][$i] = $i;
            }
        } else {
            $_pagenum = 10;     // 显示的页码
            $_offset = 2;       // 当前页偏移值
            $_from = $_to = 0;  // 开始页, 结束页
            if ($_pagenum > $page_count) {
                $_from = 1;
                $_to = $page_count;
            } else {
                $_from = $page - $_offset;
                $_to = $_from + $_pagenum - 1;
                if ($_from < 1) {
                    $_to = $page + 1 - $_from;
                    $_from = 1;
                    if ($_to - $_from < $_pagenum) {
                        $_to = $_pagenum;
                    }
                } elseif ($_to > $page_count) {
                    $_from = $page_count - $_pagenum + 1;
                    $_to = $page_count;
                }
            }
            if (! empty($url_format)) {
                $pager['page_first'] = ($page - $_offset > 1 && $_pagenum < $page_count) ? $url_format. 1 : '';
                $pager['page_prev'] = ($page > 1) ? $url_format.$page_prev : '';
                $pager['page_next'] = ($page < $page_count) ? $url_format.$page_next : '';
                $pager['page_last'] = ($_to < $page_count) ? $url_format.$page_count : '';
                $pager['page_kbd'] = ($_pagenum < $page_count) ? true : false;
                $pager['page_number'] = [];
                for ($i = $_from; $i <= $_to; $i++) {
                    $pager['page_number'][$i] = $url_format.$i;
                }
            } else {
                $pager['page_first'] = ($page - $_offset > 1 && $_pagenum < $page_count) ? build_uri($app, $uri_args, '', 1, $keywords) : '';
                $pager['page_prev'] = ($page > 1) ? build_uri($app, $uri_args, '', $page_prev, $keywords) : '';
                $pager['page_next'] = ($page < $page_count) ? build_uri($app, $uri_args, '', $page_next, $keywords) : '';
                $pager['page_last'] = ($_to < $page_count) ? build_uri($app, $uri_args, '', $page_count, $keywords) : '';
                $pager['page_kbd'] = ($_pagenum < $page_count) ? true : false;
                $pager['page_number'] = [];
                for ($i = $_from; $i <= $_to; $i++) {
                    $pager['page_number'][$i] = build_uri($app, $uri_args, '', $i, $keywords);
                }
            }
        }
        if (! empty($sch_array)) {
            $pager['search'] = $sch_array;
        } else {
            $pager['search']['category'] = $cat;
            foreach ($sch as $key => $row) {
                $pager['search'][$key] = $row;
            }
        }

        tpl()->assign('pager', $pager);
    }

    /**
     *  生成给pager.lbi赋值的数组
     *
     * @param  string  $url  分页的链接地址(必须是带有参数的地址，若不是可以伪造一个无用参数)
     * @param  array  $param  链接参数 key为参数名，value为参数值
     * @param  int  $record  记录总数量
     * @param  int  $page  当前页数
     * @param  int  $size  每页大小
     * @return array $pager
     */
    public static function get_pager($url, $param, $record_count, $page = 1, $size = 10)
    {
        $size = intval($size);
        if ($size < 1) {
            $size = 10;
        }

        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }

        $record_count = intval($record_count);

        $page_count = $record_count > 0 ? intval(ceil($record_count / $size)) : 1;
        if ($page > $page_count) {
            $page = $page_count;
        }
        // 分页样式
        $pager['styleid'] = cfg('page_style') ? intval(cfg('page_style')) : 0;

        $page_prev = ($page > 1) ? $page - 1 : 1;
        $page_next = ($page < $page_count) ? $page + 1 : $page_count;

        // 将参数合成url字串
        $param_url = '?';
        foreach ($param as $key => $value) {
            $param_url .= $key.'='.$value.'&';
        }

        $pager['url'] = $url;
        $pager['start'] = ($page - 1) * $size;
        $pager['page'] = $page;
        $pager['size'] = $size;
        $pager['record_count'] = $record_count;
        $pager['page_count'] = $page_count;

        if ($pager['styleid'] === 0) {
            $pager['page_first'] = $url.$param_url.'page=1';
            $pager['page_prev'] = $url.$param_url.'page='.$page_prev;
            $pager['page_next'] = $url.$param_url.'page='.$page_next;
            $pager['page_last'] = $url.$param_url.'page='.$page_count;
            $pager['array'] = [];
            for ($i = 1; $i <= $page_count; $i++) {
                $pager['array'][$i] = $i;
            }
        } else {
            $_pagenum = 10;     // 显示的页码
            $_offset = 2;       // 当前页偏移值
            $_from = $_to = 0;  // 开始页, 结束页
            if ($_pagenum > $page_count) {
                $_from = 1;
                $_to = $page_count;
            } else {
                $_from = $page - $_offset;
                $_to = $_from + $_pagenum - 1;
                if ($_from < 1) {
                    $_to = $page + 1 - $_from;
                    $_from = 1;
                    if ($_to - $_from < $_pagenum) {
                        $_to = $_pagenum;
                    }
                } elseif ($_to > $page_count) {
                    $_from = $page_count - $_pagenum + 1;
                    $_to = $page_count;
                }
            }
            $url_format = $url.$param_url.'page=';
            $pager['page_first'] = ($page - $_offset > 1 && $_pagenum < $page_count) ? $url_format. 1 : '';
            $pager['page_prev'] = ($page > 1) ? $url_format.$page_prev : '';
            $pager['page_next'] = ($page < $page_count) ? $url_format.$page_next : '';
            $pager['page_last'] = ($_to < $page_count) ? $url_format.$page_count : '';
            $pager['page_kbd'] = ($_pagenum < $page_count) ? true : false;
            $pager['page_number'] = [];
            for ($i = $_from; $i <= $_to; $i++) {
                $pager['page_number'][$i] = $url_format.$i;
            }
        }
        $pager['search'] = $param;

        return $pager;
    }

    /**
     * 调用调查内容
     *
     * @param  int  $id  调查的编号
     * @return array
     */
    public static function get_vote($id = '')
    {
        // 随机取得一个调查的主题
        if (empty($id)) {
            $time = TimeHelper::gmtime();
            $vote_arr = (array) DB::table('vote')
                ->select('vote_id', 'vote_name', 'can_multi', 'vote_count')
                ->where('start_time', '<=', $time)
                ->where('end_time', '>=', $time)
                ->inRandomOrder()
                ->first();
        } else {
            $vote_arr = (array) DB::table('vote')
                ->select('vote_id', 'vote_name', 'can_multi', 'vote_count')
                ->where('vote_id', $id)
                ->first();
        }

        if ($vote_arr !== false && ! empty($vote_arr)) {
            // 通过调查的ID,查询调查选项
            $res = DB::table('vote as v')
                ->select('v.*', 'o.option_id', 'o.vote_id', 'o.option_name', 'o.option_count')
                ->join('vote_option as o', 'o.vote_id', '=', 'v.vote_id')
                ->where('o.vote_id', $vote_arr['vote_id'])
                ->orderBy('o.option_order')
                ->orderByDesc('o.option_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            // 总票数
            $option_num = DB::table('vote_option')
                ->where('vote_id', $vote_arr['vote_id'])
                ->groupBy('vote_id')
                ->sum('option_count');

            $arr = [];
            $count = 100;
            foreach ($res as $idx => $row) {
                if ($option_num > 0 && $idx === count($res) - 1) {
                    $percent = $count;
                } else {
                    $percent = ($row['vote_count'] > 0 && $option_num > 0) ? round(($row['option_count'] / $option_num) * 100) : 0;

                    $count -= $percent;
                }
                $arr[$row['vote_id']]['options'][$row['option_id']]['percent'] = $percent;

                $arr[$row['vote_id']]['vote_id'] = $row['vote_id'];
                $arr[$row['vote_id']]['vote_name'] = $row['vote_name'];
                $arr[$row['vote_id']]['can_multi'] = $row['can_multi'];
                $arr[$row['vote_id']]['vote_count'] = $row['vote_count'];

                $arr[$row['vote_id']]['options'][$row['option_id']]['option_id'] = $row['option_id'];
                $arr[$row['vote_id']]['options'][$row['option_id']]['option_name'] = $row['option_name'];
                $arr[$row['vote_id']]['options'][$row['option_id']]['option_count'] = $row['option_count'];
            }

            $vote_arr['vote_id'] = (! empty($vote_arr['vote_id'])) ? $vote_arr['vote_id'] : '';

            $vote = ['id' => $vote_arr['vote_id'], 'content' => $arr];

            return $vote;
        }
    }

    /**
     * 获得浏览器名称和版本
     *
     * @return string
     */
    public static function get_user_browser()
    {
        if (empty(request()->server('HTTP_USER_AGENT'))) {
            return '';
        }

        $agent = request()->server('HTTP_USER_AGENT');
        $browser = '';
        $browser_ver = '';

        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'Internet Explorer';
            $browser_ver = $regs[1];
        } elseif (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'FireFox';
            $browser_ver = $regs[1];
        } elseif (preg_match('/Maxthon/i', $agent, $regs)) {
            $browser = '(Internet Explorer '.$browser_ver.') Maxthon';
            $browser_ver = '';
        } elseif (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
            $browser = 'Opera';
            $browser_ver = $regs[1];
        } elseif (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'OmniWeb';
            $browser_ver = $regs[2];
        } elseif (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Netscape';
            $browser_ver = $regs[2];
        } elseif (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Safari';
            $browser_ver = $regs[1];
        } elseif (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = '(Internet Explorer '.$browser_ver.') NetCaptor';
            $browser_ver = $regs[1];
        } elseif (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Lynx';
            $browser_ver = $regs[1];
        }

        if (! empty($browser)) {
            return addslashes($browser.' '.$browser_ver);
        } else {
            return 'Unknow browser';
        }
    }

    /**
     * 判断是否为搜索引擎蜘蛛
     *
     * @return string
     */
    public static function is_spider($record = true)
    {
        static $spider = null;

        if ($spider !== null) {
            return $spider;
        }

        if (empty(request()->server('HTTP_USER_AGENT'))) {
            $spider = '';

            return '';
        }

        $searchengine_bot = [
            'googlebot',
            'mediapartners-google',
            'baiduspider+',
            'msnbot',
            'yodaobot',
            'yahoo! slurp;',
            'yahoo! slurp china;',
            'iaskspider',
            'sogou web spider',
            'sogou push spider',
        ];

        $searchengine_name = [
            'GOOGLE',
            'GOOGLE ADSENSE',
            'BAIDU',
            'MSN',
            'YODAO',
            'YAHOO',
            'Yahoo China',
            'IASK',
            'SOGOU',
            'SOGOU',
        ];

        $spider = strtolower(request()->server('HTTP_USER_AGENT'));

        foreach ($searchengine_bot as $key => $value) {
            if (strpos($spider, $value) !== false) {
                $spider = $searchengine_name[$key];

                if ($record === true) {
                    DB::table('search_engine')->updateOrInsert(
                        ['date' => TimeHelper::local_date('Y-m-d'), 'searchengine' => $spider],
                        ['count' => DB::raw('count + 1')]
                    );
                }

                return $spider;
            }
        }

        $spider = '';

        return '';
    }

    /**
     * 获得客户端的操作系统
     *
     * @return void
     */
    public static function get_os()
    {
        if (empty(request()->server('HTTP_USER_AGENT'))) {
            return 'Unknown';
        }

        $agent = strtolower(request()->server('HTTP_USER_AGENT'));
        $os = '';

        if (strpos($agent, 'win') !== false) {
            if (strpos($agent, 'nt 5.1') !== false) {
                $os = 'Windows XP';
            } elseif (strpos($agent, 'nt 5.2') !== false) {
                $os = 'Windows 2003';
            } elseif (strpos($agent, 'nt 5.0') !== false) {
                $os = 'Windows 2000';
            } elseif (strpos($agent, 'nt 6.0') !== false) {
                $os = 'Windows Vista';
            } elseif (strpos($agent, 'nt') !== false) {
                $os = 'Windows NT';
            } elseif (strpos($agent, 'win 9x') !== false && strpos($agent, '4.90') !== false) {
                $os = 'Windows ME';
            } elseif (strpos($agent, '98') !== false) {
                $os = 'Windows 98';
            } elseif (strpos($agent, '95') !== false) {
                $os = 'Windows 95';
            } elseif (strpos($agent, '32') !== false) {
                $os = 'Windows 32';
            } elseif (strpos($agent, 'ce') !== false) {
                $os = 'Windows CE';
            }
        } elseif (strpos($agent, 'linux') !== false) {
            $os = 'Linux';
        } elseif (strpos($agent, 'unix') !== false) {
            $os = 'Unix';
        } elseif (strpos($agent, 'sun') !== false && strpos($agent, 'os') !== false) {
            $os = 'SunOS';
        } elseif (strpos($agent, 'ibm') !== false && strpos($agent, 'os') !== false) {
            $os = 'IBM OS/2';
        } elseif (strpos($agent, 'mac') !== false && strpos($agent, 'pc') !== false) {
            $os = 'Macintosh';
        } elseif (strpos($agent, 'powerpc') !== false) {
            $os = 'PowerPC';
        } elseif (strpos($agent, 'aix') !== false) {
            $os = 'AIX';
        } elseif (strpos($agent, 'hpux') !== false) {
            $os = 'HPUX';
        } elseif (strpos($agent, 'netbsd') !== false) {
            $os = 'NetBSD';
        } elseif (strpos($agent, 'bsd') !== false) {
            $os = 'BSD';
        } elseif (strpos($agent, 'osf1') !== false) {
            $os = 'OSF1';
        } elseif (strpos($agent, 'irix') !== false) {
            $os = 'IRIX';
        } elseif (strpos($agent, 'freebsd') !== false) {
            $os = 'FreeBSD';
        } elseif (strpos($agent, 'teleport') !== false) {
            $os = 'teleport';
        } elseif (strpos($agent, 'flashget') !== false) {
            $os = 'flashget';
        } elseif (strpos($agent, 'webzip') !== false) {
            $os = 'webzip';
        } elseif (strpos($agent, 'offline') !== false) {
            $os = 'offline';
        } else {
            $os = 'Unknown';
        }

        return $os;
    }

    /**
     * 统计访问信息
     *
     * @return void
     */
    public static function visit_stats()
    {
        if (cfg('visit_stats') && cfg('visit_stats') === 'off') {
            return;
        }
        $time = TimeHelper::gmtime();
        // 检查客户端是否存在访问统计的cookie
        $ecs_cookie = request()->cookie('ECS');
        $visit_times = (! empty($ecs_cookie['visit_times'])) ? intval($ecs_cookie['visit_times']) + 1 : 1;
        Cookie::queue('ECS[visit_times]', $visit_times, 60 * 24 * 365);

        $browser = MainHelper::get_user_browser();
        $os = MainHelper::get_os();
        $ip = BaseHelper::real_ip();
        $area = BaseHelper::ecs_geoip($ip);

        // 语言
        if (! empty(request()->server('HTTP_ACCEPT_LANGUAGE'))) {
            $pos = strpos(request()->server('HTTP_ACCEPT_LANGUAGE'), ';');
            $lang = addslashes(($pos !== false) ? substr(request()->server('HTTP_ACCEPT_LANGUAGE'), 0, $pos) : request()->server('HTTP_ACCEPT_LANGUAGE'));
        } else {
            $lang = '';
        }

        // 来源
        if (! empty(request()->server('HTTP_REFERER')) && strlen(request()->server('HTTP_REFERER')) > 9) {
            $pos = strpos(request()->server('HTTP_REFERER'), '/', 9);
            if ($pos !== false) {
                $domain = substr(request()->server('HTTP_REFERER'), 0, $pos);
                $path = substr(request()->server('HTTP_REFERER'), $pos);

                // 来源关键字
                if (! empty($domain) && ! empty($path)) {
                    MainHelper::save_searchengine_keyword($domain, $path);
                }
            } else {
                $domain = $path = '';
            }
        } else {
            $domain = $path = '';
        }

        DB::table('shop_stats')->insert([
            'ip_address' => $ip,
            'visit_times' => $visit_times,
            'browser' => $browser,
            'system' => $os,
            'language' => $lang,
            'area' => $area,
            'referer_domain' => $domain,
            'referer_path' => $path,
            'access_url' => request()->server('PHP_SELF'),
            'access_time' => $time,
        ]);
    }

    /**
     * 保存搜索引擎关键字
     *
     * @return void
     */
    public static function save_searchengine_keyword($domain, $path)
    {
        if (strpos($domain, 'google.com.tw') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'GOOGLE TAIWAN';
            $keywords = urldecode($regs[1]); // google taiwan
        }
        if (strpos($domain, 'google.cn') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'GOOGLE CHINA';
            $keywords = urldecode($regs[1]); // google china
        }
        if (strpos($domain, 'google.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'GOOGLE';
            $keywords = urldecode($regs[1]); // google
        } elseif (strpos($domain, 'baidu.') !== false && preg_match('/wd=([^&]*)/i', $path, $regs)) {
            $searchengine = 'BAIDU';
            $keywords = urldecode($regs[1]); // baidu
        } elseif (strpos($domain, 'baidu.') !== false && preg_match('/word=([^&]*)/i', $path, $regs)) {
            $searchengine = 'BAIDU';
            $keywords = urldecode($regs[1]); // baidu
        } elseif (strpos($domain, '114.vnet.cn') !== false && preg_match('/kw=([^&]*)/i', $path, $regs)) {
            $searchengine = 'CT114';
            $keywords = urldecode($regs[1]); // ct114
        } elseif (strpos($domain, 'iask.com') !== false && preg_match('/k=([^&]*)/i', $path, $regs)) {
            $searchengine = 'IASK';
            $keywords = urldecode($regs[1]); // iask
        } elseif (strpos($domain, 'soso.com') !== false && preg_match('/w=([^&]*)/i', $path, $regs)) {
            $searchengine = 'SOSO';
            $keywords = urldecode($regs[1]); // soso
        } elseif (strpos($domain, 'sogou.com') !== false && preg_match('/query=([^&]*)/i', $path, $regs)) {
            $searchengine = 'SOGOU';
            $keywords = urldecode($regs[1]); // sogou
        } elseif (strpos($domain, 'so.163.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'NETEASE';
            $keywords = urldecode($regs[1]); // netease
        } elseif (strpos($domain, 'yodao.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'YODAO';
            $keywords = urldecode($regs[1]); // yodao
        } elseif (strpos($domain, 'zhongsou.com') !== false && preg_match('/word=([^&]*)/i', $path, $regs)) {
            $searchengine = 'ZHONGSOU';
            $keywords = urldecode($regs[1]); // zhongsou
        } elseif (strpos($domain, 'search.tom.com') !== false && preg_match('/w=([^&]*)/i', $path, $regs)) {
            $searchengine = 'TOM';
            $keywords = urldecode($regs[1]); // tom
        } elseif (strpos($domain, 'live.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'MSLIVE';
            $keywords = urldecode($regs[1]); // MSLIVE
        } elseif (strpos($domain, 'tw.search.yahoo.com') !== false && preg_match('/p=([^&]*)/i', $path, $regs)) {
            $searchengine = 'YAHOO TAIWAN';
            $keywords = urldecode($regs[1]); // yahoo taiwan
        } elseif (strpos($domain, 'cn.yahoo.') !== false && preg_match('/p=([^&]*)/i', $path, $regs)) {
            $searchengine = 'YAHOO CHINA';
            $keywords = urldecode($regs[1]); // yahoo china
        } elseif (strpos($domain, 'yahoo.') !== false && preg_match('/p=([^&]*)/i', $path, $regs)) {
            $searchengine = 'YAHOO';
            $keywords = urldecode($regs[1]); // yahoo
        } elseif (strpos($domain, 'msn.com.tw') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'MSN TAIWAN';
            $keywords = urldecode($regs[1]); // msn taiwan
        } elseif (strpos($domain, 'msn.com.cn') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'MSN CHINA';
            $keywords = urldecode($regs[1]); // msn china
        } elseif (strpos($domain, 'msn.com') !== false && preg_match('/q=([^&]*)/i', $path, $regs)) {
            $searchengine = 'MSN';
            $keywords = urldecode($regs[1]); // msn
        }

        if (! empty($keywords)) {
            $gb_search = ['YAHOO CHINA', 'TOM', 'ZHONGSOU', 'NETEASE', 'SOGOU', 'SOSO', 'IASK', 'CT114', 'BAIDU'];
            if (EC_CHARSET === 'utf-8' && in_array($searchengine, $gb_search)) {
                $keywords = BaseHelper::ecs_iconv('GBK', 'UTF8', $keywords);
            }
            if (EC_CHARSET === 'gbk' && ! in_array($searchengine, $gb_search)) {
                $keywords = BaseHelper::ecs_iconv('UTF8', 'GBK', $keywords);
            }

            DB::table('search_keywords')->updateOrInsert(
                ['date' => TimeHelper::local_date('Y-m-d'), 'searchengine' => $searchengine, 'keyword' => htmlspecialchars(addslashes($keywords))],
                ['count' => DB::raw('count + 1')]
            );
        }
    }

    /**
     * 获得指定用户、商品的所有标记
     *
     * @param  int  $goods_id
     * @param  int  $user_id
     * @return array
     */
    public static function get_tags($goods_id = 0, $user_id = 0)
    {
        $arr = DB::table('user_tag')
            ->select('tag_id', 'user_id', 'tag_words', DB::raw('COUNT(tag_id) AS tag_count'))
            ->when($goods_id > 0, fn ($q) => $q->where('goods_id', $goods_id))
            ->when($user_id > 0, fn ($q) => $q->where('user_id', $user_id))
            ->groupBy('tag_words')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        return $arr;
    }

    /**
     * 获取指定主题某个模板的主题的动态模块
     *
     * @param  string  $theme  模板主题
     * @param  string  $tmp  模板名称
     * @return array()
     */
    public static function get_dyna_libs($theme, $tmp)
    {
        $tmp_arr = explode('.', $tmp);
        $ext = end($tmp_arr);
        $tmp = basename($tmp, ".$ext");
        $res = DB::table('template')
            ->select('region', 'library', 'sort_order', 'id', 'number', 'type')
            ->where('theme', $theme)
            ->where('filename', $tmp)
            ->where('type', '>', 0)
            ->where('remarks', '')
            ->orderBy('region')
            ->orderBy('library')
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $dyna_libs = [];
        foreach ($res as $row) {
            $dyna_libs[$row['region']][$row['library']][] = [
                'id' => $row['id'],
                'number' => $row['number'],
                'type' => $row['type'],
            ];
        }

        return $dyna_libs;
    }

    /**
     * 替换动态模块
     *
     * @param  string  $matches  匹配内容
     * @return string 结果
     */
    public static function dyna_libs_replace($matches)
    {
        $key = '/'.$matches[1];

        if ($row = array_shift($GLOBALS['libs'][$key])) {
            $str = '';
            switch ($row['type']) {
                case 1:
                    // 分类的商品
                    $str = '{assign var="cat_goods" value=$cat_goods_'.$row['id'].'}{assign var="goods_cat" value=$goods_cat_'.$row['id'].'}';
                    break;
                case 2:
                    // 品牌的商品
                    $str = '{assign var="brand_goods" value=$brand_goods_'.$row['id'].'}{assign var="goods_brand" value=$goods_brand_'.$row['id'].'}';
                    break;
                case 3:
                    // 文章列表
                    $str = '{assign var="articles" value=$articles_'.$row['id'].'}{assign var="articles_cat" value=$articles_cat_'.$row['id'].'}';
                    break;
                case 4:
                    // 广告位
                    $str = '{assign var="ads_id" value='.$row['id'].'}{assign var="ads_num" value='.$row['number'].'}';
                    break;
            }

            return $str.$matches[0];
        } else {
            return $matches[0];
        }
    }

    /**
     * 处理上传文件，并返回上传图片名(上传失败时返回图片名为空）
     *
     * @param  array  $upload  $_FILES 数组
     * @param  array  $type  图片所属类别，即data目录下的文件夹名
     * @return string 上传图片名
     */
    public static function upload_file($upload, $type)
    {
        if (! empty($upload['tmp_name'])) {
            $ftype = BaseHelper::check_file_type($upload['tmp_name'], $upload['name'], '|png|jpg|jpeg|gif|doc|xls|txt|zip|ppt|pdf|rar|docx|xlsx|pptx|');
            if (! empty($ftype)) {
                $name = date('Ymd');
                for ($i = 0; $i < 6; $i++) {
                    $name .= chr(mt_rand(97, 122));
                }

                $name = Session::get('user_id').'_'.$name.'.'.$ftype;

                $target = ROOT_PATH.DATA_DIR.'/'.$type.'/'.$name;
                if (! BaseHelper::move_upload_file($upload['tmp_name'], $target)) {
                    err()->add(lang('upload_file_error'), 1);

                    return false;
                } else {
                    return $name;
                }
            } else {
                err()->add(lang('upload_file_type'), 1);

                return false;
            }
        } else {
            err()->add(lang('upload_file_error'));

            return false;
        }
    }

    /**
     * 将一个形如+10, 10, -10, 10%的字串转换为相应数字，并返回操作符号
     *
     * @param string      str     要格式化的数据
     * @param char        operate 操作符号，只能返回‘+’或‘*’;
     * @return float value   浮点数
     */
    public static function parse_rate_value($str, &$operate)
    {
        $operate = '+';
        $is_rate = false;

        $str = trim($str);
        if (empty($str)) {
            return 0;
        }
        if ($str[strlen($str) - 1] === '%') {
            $value = floatval($str);
            if ($value > 0) {
                $operate = '*';

                return $value / 100;
            } else {
                return 0;
            }
        } else {
            return floatval($str);
        }
    }

    /**
     * 重新计算购物车中的商品价格：目的是当用户登录时享受会员价格，当用户退出登录时不享受会员价格
     * 如果商品有促销，价格不变
     *
     * @return void
     */
    public static function recalculate_price()
    {
        // 取得有可能改变价格的商品：除配件和赠品之外的商品
        $res = DB::table('user_cart as c')
            ->select('c.rec_id', 'c.goods_id', 'c.goods_attr_id', 'g.promote_price', 'g.promote_start_date', 'c.goods_number', 'g.promote_end_date', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS member_price"))
            ->leftJoin('goods as g', 'g.goods_id', '=', 'c.goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('session_id', SESS_ID)
            ->where('c.parent_id', 0)
            ->where('c.is_gift', 0)
            ->where('c.goods_id', '>', 0)
            ->where('c.rec_type', CART_GENERAL_GOODS)
            ->where('c.extension_code', '<>', 'package_buy')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            $attr_id = empty($row['goods_attr_id']) ? [] : explode(',', $row['goods_attr_id']);

            $goods_price = CommonHelper::get_final_price($row['goods_id'], $row['goods_number'], true, $attr_id);

            DB::table('user_cart')
                ->where('goods_id', $row['goods_id'])
                ->where('session_id', SESS_ID)
                ->where('rec_id', $row['rec_id'])
                ->update(['goods_price' => $goods_price]);
        }

        // 删除赠品，重新选择
        DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('is_gift', '>', 0)
            ->delete();
    }

    /**
     * 查询评论内容
     *
     * @params  integer     $id
     * @params  integer     $type
     * @params  integer     $page
     *
     * @return array
     */
    public static function assign_comment($id, $type, $page = 1)
    {
        // 取得评论列表
        $count = DB::table('comment')
            ->where('id_value', $id)
            ->where('comment_type', $type)
            ->where('status', 1)
            ->where('parent_id', 0)
            ->count();
        $size = ! empty(cfg('comments_number')) ? cfg('comments_number') : 5;

        $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;

        $res = DB::table('comment')
            ->where('id_value', $id)
            ->where('comment_type', $type)
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderByDesc('comment_id')
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        $ids = '';
        foreach ($res as $row) {
            $ids .= $ids ? ",$row[comment_id]" : $row['comment_id'];
            $arr[$row['comment_id']]['id'] = $row['comment_id'];
            $arr[$row['comment_id']]['email'] = $row['email'];
            $arr[$row['comment_id']]['username'] = $row['user_name'];
            $arr[$row['comment_id']]['content'] = str_replace('\r\n', '<br />', htmlspecialchars($row['content']));
            $arr[$row['comment_id']]['content'] = nl2br(str_replace('\n', '<br />', $arr[$row['comment_id']]['content']));
            $arr[$row['comment_id']]['rank'] = $row['comment_rank'];
            $arr[$row['comment_id']]['add_time'] = TimeHelper::local_date(cfg('time_format'), $row['add_time']);
        }
        // 取得已有回复的评论
        if ($ids) {
            $res = DB::table('comment')
                ->whereIn('parent_id', explode(',', $ids))
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            foreach ($res as $row) {
                $arr[$row['parent_id']]['re_content'] = nl2br(str_replace('\n', '<br />', htmlspecialchars($row['content'])));
                $arr[$row['parent_id']]['re_add_time'] = TimeHelper::local_date(cfg('time_format'), $row['add_time']);
                $arr[$row['parent_id']]['re_email'] = $row['email'];
                $arr[$row['parent_id']]['re_username'] = $row['user_name'];
            }
        }
        // 分页样式
        // $pager['styleid'] = cfg('page_style')? intval(cfg('page_style')) : 0;
        $pager['page'] = $page;
        $pager['size'] = $size;
        $pager['record_count'] = $count;
        $pager['page_count'] = $page_count;
        $pager['page_first'] = "javascript:gotoPage(1,$id,$type)";
        $pager['page_prev'] = $page > 1 ? 'javascript:gotoPage('.($page - 1).",$id,$type)" : 'javascript:;';
        $pager['page_next'] = $page < $page_count ? 'javascript:gotoPage('.($page + 1).",$id,$type)" : 'javascript:;';
        $pager['page_last'] = $page < $page_count ? 'javascript:gotoPage('.$page_count.",$id,$type)" : 'javascript:;';

        $cmt = ['comments' => $arr, 'pager' => $pager];

        return $cmt;
    }

    /**
     * 将一个本地时间戳转成GMT时间戳
     *
     * @param  int  $time
     * @return int $gmt_time;
     */
    public static function time2gmt($time)
    {
        return strtotime(gmdate('Y-m-d H:i:s', $time));
    }

    /**
     * 查询会员的红包金额
     *
     * @param  int  $user_id
     * @return void
     */
    public static function get_user_bonus($user_id = 0)
    {
        if ($user_id === 0) {
            $user_id = Session::get('user_id');
        }

        $row = (array) DB::table('user_bonus as ub')
            ->select(DB::raw('SUM(bt.type_money) AS bonus_value'), DB::raw('COUNT(*) AS bonus_count'))
            ->join('activity_bonus as bt', 'ub.bonus_type_id', '=', 'bt.type_id')
            ->where('ub.user_id', $user_id)
            ->where('ub.order_id', 0)
            ->first();

        return $row;
    }

    /**
     * 保存推荐uid
     *
     * @param void
     * @return void
     */
    public static function set_affiliate()
    {
        $config = unserialize(cfg('affiliate'));
        $u = request()->input('u');
        if (! empty($u) && $config['on'] === 1) {
            if (! empty($config['config']['expire'])) {
                if ($config['config']['expire_unit'] === 'hour') {
                    $c = 1;
                } elseif ($config['config']['expire_unit'] === 'day') {
                    $c = 24;
                } elseif ($config['config']['expire_unit'] === 'week') {
                    $c = 24 * 7;
                } else {
                    $c = 1;
                }
                $minutes = 60 * $config['config']['expire'] * $c;
                Cookie::queue('phpmall_affiliate_uid', intval($u), $minutes, '', '', false, true);
            } else {
                Cookie::queue('phpmall_affiliate_uid', intval($u), 24 * 60, '', '', false, true); // 过期时间为 1 天
            }
        }
    }

    /**
     * 获取推荐uid
     *
     * @param void
     * @return int
     */
    public static function get_affiliate()
    {
        if (! empty(request()->cookie('phpmall_affiliate_uid'))) {
            $uid = intval(request()->cookie('phpmall_affiliate_uid'));
            if (DB::table('user')->where('user_id', $uid)->exists()) {
                return $uid;
            } else {
                Cookie::expire('phpmall_affiliate_uid');
            }
        }

        return 0;
    }

    /**
     * 获得指定分类同级的所有分类以及该分类下的子分类
     *
     * @param  int  $cat_id  分类编号
     * @return array
     */
    public static function article_categories_tree($cat_id = 0)
    {
        if ($cat_id > 0) {
            $parent_id = DB::table('article_cat')->where('cat_id', $cat_id)->value('parent_id');
        } else {
            $parent_id = 0;
        }

        /*
         判断当前分类中全是是否是底级分类，
         如果是取出底级分类上级分类，
         如果不是取当前分类及其下的子分类
        */
        if (DB::table('article_cat')->where('parent_id', $parent_id)->exists()) {
            // 获取当前分类及其子分类
            $res = DB::table('article_cat as a')
                ->select('a.cat_id', 'a.cat_name', 'a.sort_order AS parent_order', 'b.cat_id AS child_id', 'b.cat_name AS child_name', 'b.sort_order AS child_order')
                ->leftJoin('article_cat as b', 'b.parent_id', '=', 'a.cat_id')
                ->where('a.parent_id', $parent_id)
                ->where('a.cat_type', 1)
                ->orderBy('parent_order')
                ->orderBy('a.cat_id')
                ->orderBy('child_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        } else {
            // 获取当前分类及其父分类
            $res = DB::table('article_cat as a')
                ->select('a.cat_id', 'a.cat_name', 'b.cat_id AS child_id', 'b.cat_name AS child_name', 'b.sort_order')
                ->leftJoin('article_cat as b', 'b.parent_id', '=', 'a.cat_id')
                ->where('b.parent_id', $parent_id)
                ->where('b.cat_type', 1)
                ->orderBy('b.sort_order')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        }

        $cat_arr = [];
        foreach ($res as $row) {
            $cat_arr[$row['cat_id']]['id'] = $row['cat_id'];
            $cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
            $cat_arr[$row['cat_id']]['url'] = build_uri('article_cat', ['acid' => $row['cat_id']], $row['cat_name']);

            if ($row['child_id'] != null) {
                $cat_arr[$row['cat_id']]['children'][$row['child_id']]['id'] = $row['child_id'];
                $cat_arr[$row['cat_id']]['children'][$row['child_id']]['name'] = $row['child_name'];
                $cat_arr[$row['cat_id']]['children'][$row['child_id']]['url'] = build_uri('article_cat', ['acid' => $row['child_id']], $row['child_name']);
            }
        }

        return $cat_arr;
    }

    /**
     * 获得指定文章分类的所有上级分类
     *
     * @param  int  $cat  分类编号
     * @return array
     */
    public static function get_article_parent_cats($cat)
    {
        if ($cat === 0) {
            return [];
        }

        $arr = DB::table('article_cat')
            ->select('cat_id', 'cat_name', 'parent_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (empty($arr)) {
            return [];
        }

        $index = 0;
        $cats = [];

        while (1) {
            foreach ($arr as $row) {
                if ($cat === $row['cat_id']) {
                    $cat = $row['parent_id'];

                    $cats[$index]['cat_id'] = $row['cat_id'];
                    $cats[$index]['cat_name'] = $row['cat_name'];

                    $index++;
                    break;
                }
            }

            if ($index === 0 || $cat === 0) {
                break;
            }
        }

        return $cats;
    }

    /**
     * 取得某模板某库设置的数量
     *
     * @param  string  $template  模板名，如index
     * @param  string  $library  库名，如recommend_best
     * @param  int  $def_num  默认数量：如果没有设置模板，显示的数量
     * @return int 数量
     */
    public static function get_library_number($library, $template = null)
    {
        if (empty($template)) {
            $template = basename(request()->path());
            $template = substr($template, 0, strrpos($template, '.'));
        }
        $template = addslashes($template);

        static $lib_list = [];

        // 如果没有该模板的信息，取得该模板的信息
        if (! isset($lib_list[$template])) {
            $lib_list[$template] = [];
            $res = DB::table('template')
                ->select('library', 'number')
                ->where('theme', cfg('template'))
                ->where('filename', $template)
                ->where('remarks', '')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            foreach ($res as $row) {
                $lib = basename(strtolower(substr($row['library'], 0, strrpos($row['library'], '.'))));
                $lib_list[$template][$lib] = $row['number'];
            }
        }

        $num = 0;
        if (isset($lib_list[$template][$library])) {
            $num = intval($lib_list[$template][$library]);
        } else {
            // 模板设置文件查找默认值
            static $static_page_libs = null;
            if ($static_page_libs === null) {
                $static_page_libs = $GLOBALS['page_libs'] ?? null;
            }
            $lib = '/library/'.$library.'';

            $num = isset($static_page_libs[$template][$lib]) ? $static_page_libs[$template][$lib] : 3;
        }

        return $num;
    }

    /**
     * 取得自定义导航栏列表
     *
     * @param  string  $type  位置，如top、bottom、middle
     * @return array 列表
     */
    public static function get_navigator($ctype = '', $catlist = [])
    {
        $res = DB::table('shop_nav')
            ->where('ifshow', '1')
            ->orderBy('type')
            ->orderBy('vieworder')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $request_uri = request()->server('REQUEST_URI');
        $cur_url = substr(strrchr($request_uri, '/'), 1);

        if (intval(cfg('rewrite'))) {
            if (strpos($cur_url, '-')) {
                preg_match('/([a-z]*)-([0-9]*)/', $cur_url, $matches);
                $cur_url = $matches[1].'.php?id='.$matches[2];
            }
        } else {
            $cur_url = substr(strrchr($request_uri, '/'), 1);
        }

        $noindex = false;
        $active = 0;
        $navlist = [
            'top' => [],
            'middle' => [],
            'bottom' => [],
        ];
        foreach ($res as $row) {
            $navlist[$row['type']][] = [
                'name' => $row['name'],
                'opennew' => $row['opennew'],
                'url' => $row['url'],
                'ctype' => $row['ctype'],
                'cid' => $row['cid'],
            ];
        }

        // 遍历自定义是否存在currentPage
        foreach ($navlist['middle'] as $k => $v) {
            $condition = empty($ctype) ? (strpos($cur_url, $v['url']) === 0) : (strpos($cur_url, $v['url']) === 0 && strlen($cur_url) === strlen($v['url']));
            if ($condition) {
                $navlist['middle'][$k]['active'] = 1;
                $noindex = true;
                $active += 1;
            }
        }

        if (! empty($ctype) && $active < 1) {
            foreach ($catlist as $key => $val) {
                foreach ($navlist['middle'] as $k => $v) {
                    if (! empty($v['ctype']) && $v['ctype'] === $ctype && $v['cid'] === $val && $active < 1) {
                        $navlist['middle'][$k]['active'] = 1;
                        $noindex = true;
                        $active += 1;
                    }
                }
            }
        }

        if ($noindex === false) {
            $navlist['config']['index'] = 1;
        }

        return $navlist;
    }

    public static function url_domain()
    {
        $php_self = request()->server('PHP_SELF');
        $curr = strpos($php_self, ADMIN_PATH.'/') !== false ?
            preg_replace('/(.*)('.ADMIN_PATH.')(\/?)(.)*/i', '\1', dirname($php_self)) :
            dirname($php_self);

        $root = str_replace('\\', '/', $curr);

        if (substr($root, -1) != '/') {
            $root .= '/';
        }

        return $root;
    }

    // 更新离线购物车
    public static function update_cart_offline()
    {
        if (! Session::has('user_id')) {
            return false;
        }
        $user_id = intval(Session::get('user_id'));

        // 获取离线购物车
        $offline_carts = DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('user_id', 0)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (! $offline_carts) { // 无需合并
            return true;
        }

        // 获取会员购物车数据
        $online_carts = DB::table('user_cart')
            ->where('user_id', $user_id)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (! $online_carts) { // 离线转在线
            DB::table('user_cart')
                ->where('session_id', SESS_ID)
                ->update(['user_id' => $user_id]);
        }

        // 合并购物车相同的商品
        $offcart = [];
        foreach ($offline_carts as $offkey => $offval) {
            if (! $offval['goods_id'] || ! $offval['goods_number']) {
                continue;
            }
            $key = $offval['goods_id'].'_'.$offval['product_id'];
            $offcart[$key] = $offval;
        }

        foreach ($online_carts as $onkey => $onval) {
            if (! $onval['goods_id'] || ! $onval['goods_number']) {
                continue;
            }
            $key = $onval['goods_id'].'_'.$onval['product_id'];
            if ($offcart[$key]) {
                DB::table('user_cart')
                    ->where('rec_id', $onval['rec_id'])
                    ->increment('goods_number', (int) $offcart[$key]['goods_number']);
                DB::table('user_cart')
                    ->where('rec_id', $offcart[$key]['rec_id'])
                    ->delete();
                unset($offcart[$key]);
            }
        }
        // 不重复的商品转成在线购物车
        if (count($offcart) > 0) {
            $offcart = array_values($offcart); // 初始化数组的key
            $rec_id = [];
            for ($i = count($offcart) - 1; $i >= 0; $i--) {
                $rec_id[] = $offcart[$i]['rec_id'];
            }
            $rec_id = array_unique(array_filter($rec_id));
            DB::table('user_cart')->whereIn('rec_id', $rec_id)->update(['user_id' => $user_id]);
        }

        return true;
    }
}
