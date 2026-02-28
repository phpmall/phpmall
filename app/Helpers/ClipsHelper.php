<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Bundles\User\Entities\UserBookingEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ClipsHelper
{
    /**
     *  获取指定用户的收藏商品列表
     *
     * @param  int  $user_id  用户ID
     * @param  int  $num  列表最大数量
     * @param  int  $start  列表其实位置
     * @return array $arr
     */
    public static function get_collection_goods($user_id, $num = 10, $start = 0)
    {
        $res = DB::table('user_collect as c')
            ->select([
                'g.goods_id',
                'g.goods_name',
                'g.market_price',
                'g.shop_price as org_price',
                DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') as shop_price"),
                'g.promote_price',
                'g.promote_start_date',
                'g.promote_end_date',
                'c.rec_id',
                'c.is_attention',
            ])
            ->leftJoin('goods as g', 'g.goods_id', '=', 'c.goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('c.user_id', $user_id)
            ->orderByDesc('c.rec_id')
            ->offset($start)
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $goods_list = [];
        foreach ($res as $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }

            $goods_list[$row['goods_id']]['rec_id'] = $row['rec_id'];
            $goods_list[$row['goods_id']]['is_attention'] = $row['is_attention'];
            $goods_list[$row['goods_id']]['goods_id'] = $row['goods_id'];
            $goods_list[$row['goods_id']]['goods_name'] = $row['goods_name'];
            $goods_list[$row['goods_id']]['market_price'] = CommonHelper::price_format($row['market_price']);
            $goods_list[$row['goods_id']]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $goods_list[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? CommonHelper::price_format($promote_price) : '';
            $goods_list[$row['goods_id']]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
        }

        return $goods_list;
    }

    /**
     *  查看此商品是否已进行过缺货登记
     *
     * @param  int  $user_id  用户ID
     * @param  int  $goods_id  商品ID
     */
    public static function get_booking_rec(int $user_id, int $goods_id): int
    {
        return DB::table('user_booking')->where([
            UserBookingEntity::getUserId => $user_id,
            UserBookingEntity::getGoodsId => $goods_id,
            UserBookingEntity::getIsDispose => 0,
        ])->count();
    }

    /**
     *  获取指定用户的留言
     *
     * @param  int  $user_id  用户ID
     * @param  int  $user_name  用户名
     * @param  int  $num  列表最大数量
     * @param  int  $start  列表其实位置
     * @return array $msg            留言及回复列表
     * @return string $order_id       订单ID
     */
    public static function get_message_list($user_id, $user_name, $num, $start, $order_id = 0)
    {
        $query = DB::table('feedback')
            ->where('parent_id', 0)
            ->orderByDesc('msg_time');

        if ($order_id) {
            $query->where('order_id', $order_id)->where('user_id', $user_id);
        } else {
            $query->where('user_id', $user_id)->where('user_name', Session::get('user_name'))->where('order_id', 0);
        }

        $res = $query->offset($start)
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $rows) {
            // 取得留言的回复
            // if (empty($order_id))
            // {
            $reply = (array) DB::table('feedback')
                ->select('user_name', 'user_email', 'msg_time', 'msg_content')
                ->where('parent_id', $rows['msg_id'])
                ->first();

            if ($reply) {
                $msg[$rows['msg_id']]['re_user_name'] = $reply['user_name'];
                $msg[$rows['msg_id']]['re_user_email'] = $reply['user_email'];
                $msg[$rows['msg_id']]['re_msg_time'] = TimeHelper::local_date(cfg('time_format'), $reply['msg_time']);
                $msg[$rows['msg_id']]['re_msg_content'] = nl2br(htmlspecialchars($reply['msg_content']));
            }
            // }

            $msg[$rows['msg_id']]['msg_content'] = nl2br(htmlspecialchars($rows['msg_content']));
            $msg[$rows['msg_id']]['msg_time'] = TimeHelper::local_date(cfg('time_format'), $rows['msg_time']);
            $msg[$rows['msg_id']]['msg_type'] = $order_id ? $rows['user_name'] : lang('type')[$rows['msg_type']];
            $msg[$rows['msg_id']]['msg_title'] = nl2br(htmlspecialchars($rows['msg_title']));
            $msg[$rows['msg_id']]['message_img'] = $rows['message_img'];
            $msg[$rows['msg_id']]['order_id'] = $rows['order_id'];
        }

        return $msg;
    }

    /**
     *  添加留言函数
     *
     * @param  array  $message
     * @return bool $bool
     */
    public static function add_message($message)
    {
        $upload_size_limit = cfg('upload_size_limit') === '-1' ? ini_get('upload_max_filesize') : cfg('upload_size_limit');
        $status = 1 - cfg('message_check');

        $last_char = strtolower($upload_size_limit[strlen($upload_size_limit) - 1]);

        switch ($last_char) {
            case 'm':
                $upload_size_limit *= 1024 * 1024;
                break;
            case 'k':
                $upload_size_limit *= 1024;
                break;
        }

        if ($message['upload']) {
            if ($_FILES['message_img']['size'] / 1024 > $upload_size_limit) {
                err()->add(sprintf(lang('upload_file_limit'), $upload_size_limit));

                return false;
            }
            $img_name = MainHelper::upload_file($_FILES['message_img'], 'feedbackimg');

            if ($img_name === false) {
                return false;
            }
        } else {
            $img_name = '';
        }

        if (empty($message['msg_title'])) {
            err()->add(lang('msg_title_empty'));

            return false;
        }

        DB::table('feedback')->insert([
            'parent_id' => 0,
            'user_id' => $message['user_id'],
            'user_name' => $message['user_name'],
            'user_email' => $message['user_email'],
            'msg_title' => $message['msg_title'],
            'msg_type' => $message['msg_type'],
            'msg_status' => $status,
            'msg_content' => $message['msg_content'],
            'msg_time' => TimeHelper::gmtime(),
            'message_img' => $img_name,
            'order_id' => $message['order_id'],
            'msg_area' => $message['msg_area'],
        ]);

        return true;
    }

    /**
     *  获取用户的tags
     *
     * @param  int  $user_id  用户ID
     * @return array $arr            tags列表
     */
    public static function get_user_tags($user_id = 0)
    {
        if (empty($user_id)) {
            $GLOBALS['error_no'] = 1;

            return false;
        }

        $tags = MainHelper::get_tags(0, $user_id);

        if (! empty($tags)) {
            ClipsHelper::color_tag($tags);
        }

        return $tags;
    }

    /**
     *  验证性的删除某个tag
     *
     * @param  int  $tag_words  tag的ID
     * @param  int  $user_id  用户的ID
     * @return bool bool
     */
    public static function delete_tag($tag_words, $user_id)
    {
        return (bool) DB::table('user_tag')
            ->where('tag_words', $tag_words)
            ->where('user_id', $user_id)
            ->delete();
    }

    /**
     *  获取某用户的缺货登记列表
     *
     * @param  int  $user_id  用户ID
     * @param  int  $num  列表最大数量
     * @param  int  $start  列表其实位置
     * @return array $booking
     */
    public static function get_booking_list($user_id, $num, $start)
    {
        $booking = [];
        $res = DB::table('user_booking as bg')
            ->select('bg.rec_id', 'bg.goods_id', 'bg.goods_number', 'bg.booking_time', 'bg.dispose_note', 'g.goods_name')
            ->leftJoin('goods as g', 'bg.goods_id', '=', 'g.goods_id')
            ->where('bg.user_id', $user_id)
            ->orderByDesc('bg.booking_time')
            ->offset($start)
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            if (empty($row['dispose_note'])) {
                $row['dispose_note'] = 'N/A';
            }
            $booking[] = ['rec_id' => $row['rec_id'],
                'goods_name' => $row['goods_name'],
                'goods_number' => $row['goods_number'],
                'booking_time' => TimeHelper::local_date(cfg('date_format'), $row['booking_time']),
                'dispose_note' => $row['dispose_note'],
                'url' => build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name'])];
        }

        return $booking;
    }

    /**
     *  获取某用户的缺货登记列表
     *
     * @param  int  $goods_id  商品ID
     * @return array $info
     */
    public static function get_goodsinfo($goods_id)
    {
        $info = [];
        $info['goods_name'] = DB::table('goods')->where('goods_id', $goods_id)->value('goods_name');
        $info['goods_number'] = 1;
        $info['id'] = $goods_id;

        if (! empty(Session::get('user_id'))) {
            $row = [];
            $row = (array) DB::table('user_address as ua')
                ->select('ua.consignee', 'ua.email', 'ua.tel', 'ua.mobile')
                ->leftJoin('user as u', 'u.address_id', '=', 'ua.address_id')
                ->where('u.user_id', Session::get('user_id'))
                ->first();
            $info['consignee'] = empty($row['consignee']) ? '' : $row['consignee'];
            $info['email'] = empty($row['email']) ? '' : $row['email'];
            $info['tel'] = empty($row['mobile']) ? (empty($row['tel']) ? '' : $row['tel']) : $row['mobile'];
        }

        return $info;
    }

    /**
     *  验证删除某个收藏商品
     *
     * @param  int  $booking_id  缺货登记的ID
     * @param  int  $user_id  会员的ID
     * @return bool $bool
     */
    public static function delete_booking($booking_id, $user_id)
    {
        return (bool) DB::table('user_booking')
            ->where('rec_id', $booking_id)
            ->where('user_id', $user_id)
            ->delete();
    }

    /**
     * 添加缺货登记记录到数据表
     *
     * @param  array  $booking
     */
    public static function add_booking($booking)
    {
        return DB::table('user_booking')->insertGetId([
            'user_id' => Session::get('user_id'),
            'email' => $booking['email'],
            'linkman' => $booking['linkman'],
            'tel' => $booking['tel'],
            'goods_id' => $booking['goods_id'],
            'goods_desc' => $booking['desc'],
            'goods_amount' => $booking['goods_amount'],
            'booking_time' => TimeHelper::gmtime(),
            'is_dispose' => 0,
            'dispose_note' => '',
            'dispose_user' => 0,
            'dispose_time' => 0,
        ]);
    }

    /**
     * 插入会员账目明细
     *
     * @param  array  $surplus  会员余额信息
     * @param  string  $amount  余额
     * @return int
     */
    public static function insert_user_account($surplus, $amount)
    {
        return DB::table('user_account')->insertGetId([
            'user_id' => $surplus['user_id'],
            'admin_user' => '',
            'amount' => $amount,
            'add_time' => TimeHelper::gmtime(),
            'paid_time' => 0,
            'admin_note' => '',
            'user_note' => $surplus['user_note'],
            'process_type' => $surplus['process_type'],
            'payment' => $surplus['payment'],
            'is_paid' => 0,
        ]);
    }

    /**
     * 更新会员账目明细
     *
     * @param  array  $surplus  会员余额信息
     * @return int
     */
    public static function update_user_account($surplus)
    {
        DB::table('user_account')
            ->where('id', $surplus['rec_id'])
            ->update([
                'amount' => $surplus['amount'],
                'user_note' => $surplus['user_note'],
                'payment' => $surplus['payment'],
            ]);

        return $surplus['rec_id'];
    }

    /**
     * 将支付LOG插入数据表
     *
     * @param  int  $id  订单编号
     * @param  float  $amount  订单金额
     * @param  int  $type  支付类型
     * @param  int  $is_paid  是否已支付
     * @return int
     */
    public static function insert_pay_log($id, $amount, $type = PAY_SURPLUS, $is_paid = 0)
    {
        return DB::table('order_pay')->insertGetId([
            'order_id' => $id,
            'order_amount' => $amount,
            'order_type' => $type,
            'is_paid' => $is_paid,
        ]);
    }

    /**
     * 取得上次未支付的pay_lig_id
     *
     * @param  array  $surplus_id  余额记录的ID
     * @param  array  $pay_type  支付的类型：预付款/订单支付
     * @return int
     */
    public static function get_paylog_id($surplus_id, $pay_type = PAY_SURPLUS)
    {
        return DB::table('order_pay')
            ->where('order_id', $surplus_id)
            ->where('order_type', $pay_type)
            ->where('is_paid', 0)
            ->value('log_id');
    }

    /**
     * 根据ID获取当前余额操作信息
     *
     * @param  int  $surplus_id  会员余额的ID
     * @return int
     */
    public static function get_surplus_info($surplus_id)
    {
        return (array) DB::table('user_account')
            ->where('id', $surplus_id)
            ->first();
    }

    /**
     * 取得已安装的支付方式(其中不包括线下支付的)
     *
     * @param  bool  $include_balance  是否包含余额支付（冲值时不应包括）
     * @return array 已安装的配送方式列表
     */
    public static function get_online_payment_list($include_balance = true)
    {
        $query = DB::table('payment')
            ->select('pay_id', 'pay_code', 'pay_name', 'pay_fee', 'pay_desc')
            ->where('enabled', 1)
            ->where('is_cod', '<>', 1);

        if (! $include_balance) {
            $query->where('pay_code', '<>', 'balance');
        }

        return $query->get()->map(fn ($item) => (array) $item)->all();
    }

    /**
     * 查询会员余额的操作记录
     *
     * @param  int  $user_id  会员ID
     * @param  int  $num  每页显示数量
     * @param  int  $start  开始显示的条数
     * @return array
     */
    public static function get_account_log($user_id, $num, $start)
    {
        $account_log = [];
        $res = DB::table('user_account')
            ->where('user_id', $user_id)
            ->whereIn('process_type', [SURPLUS_SAVE, SURPLUS_RETURN])
            ->orderByDesc('add_time')
            ->offset($start)
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if ($res) {
            foreach ($res as $rows) {
                $rows['add_time'] = TimeHelper::local_date(cfg('date_format'), $rows['add_time']);
                $rows['admin_note'] = nl2br(htmlspecialchars($rows['admin_note']));
                $rows['short_admin_note'] = ($rows['admin_note'] > '') ? Str::substr($rows['admin_note'], 30) : 'N/A';
                $rows['user_note'] = nl2br(htmlspecialchars($rows['user_note']));
                $rows['short_user_note'] = ($rows['user_note'] > '') ? Str::substr($rows['user_note'], 30) : 'N/A';
                $rows['pay_status'] = ($rows['is_paid'] === 0) ? lang('un_confirm') : lang('is_confirm');
                $rows['amount'] = CommonHelper::price_format(abs($rows['amount']), false);

                // 会员的操作类型： 冲值，提现
                if ($rows['process_type'] === 0) {
                    $rows['type'] = lang('surplus_type_0');
                } else {
                    $rows['type'] = lang('surplus_type_1');
                }

                // 支付方式的ID
                $pid = DB::table('payment')
                    ->where('pay_name', $rows['payment'])
                    ->where('enabled', 1)
                    ->value('pay_id');

                // 如果是预付款而且还没有付款, 允许付款
                if (($rows['is_paid'] === 0) && ($rows['process_type'] === 0)) {
                    $rows['handle'] = '<a href="user.php?act=pay&id='.$rows['id'].'&pid='.$pid.'">'.lang('pay').'</a>';
                }

                $account_log[] = $rows;
            }

            return $account_log;
        } else {
            return false;
        }
    }

    /**
     *  删除未确认的会员帐目信息
     *
     * @param  int  $rec_id  会员余额记录的ID
     * @param  int  $user_id  会员的ID
     * @return bool
     */
    public static function del_user_account($rec_id, $user_id)
    {
        return (bool) DB::table('user_account')
            ->where('is_paid', 0)
            ->where('id', $rec_id)
            ->where('user_id', $user_id)
            ->delete();
    }

    /**
     * 查询会员余额的数量
     *
     * @param  int  $user_id  会员ID
     * @return int
     */
    public static function get_user_surplus($user_id)
    {
        return (float) DB::table('user_account_log')
            ->where('user_id', $user_id)
            ->sum('user_money');
    }

    /**
     * 获取用户中心默认页面所需的数据
     *
     * @param  int  $user_id  用户ID
     * @return array $info               默认页面所需资料数组
     */
    public static function get_user_default($user_id)
    {
        $user_bonus = MainHelper::get_user_bonus();

        $row = (array) DB::table('user')
            ->select('pay_points', 'user_money', 'credit_line', 'last_login', 'is_validated')
            ->where('user_id', $user_id)
            ->first();
        $info = [];
        $info['username'] = stripslashes(Session::get('user_name'));
        $info['shop_name'] = cfg('shop_name');
        $info['integral'] = $row['pay_points'].cfg('integral_name');
        // 增加是否开启会员邮件验证开关
        $info['is_validate'] = (cfg('member_email_validate') && ! $row['is_validated']) ? 0 : 1;
        $info['credit_line'] = $row['credit_line'];
        $info['formated_credit_line'] = CommonHelper::price_format($info['credit_line'], false);

        // 如果$_SESSION中时间无效说明用户是第一次登录。取当前登录时间。
        $last_time = Session::has('last_time') ? Session::get('last_time') : $row['last_login'];

        if ($last_time === 0) {
            $last_time = TimeHelper::gmtime();
            Session::put('last_time', $last_time);
        }

        $info['last_time'] = TimeHelper::local_date(cfg('time_format'), $last_time);
        $info['surplus'] = CommonHelper::price_format($row['user_money'], false);
        $info['bonus'] = sprintf(lang('user_bonus_info'), $user_bonus['bonus_count'], CommonHelper::price_format($user_bonus['bonus_value'], false));

        $info['order_count'] = DB::table('order_info')
            ->where('user_id', $user_id)
            ->where('add_time', '>', TimeHelper::local_strtotime('-1 months'))
            ->count();

        $info['shipped_order'] = DB::table('order_info')
            ->select('order_id', 'order_sn')
            ->where('user_id', $user_id)
            ->where('shipping_time', '>', $last_time)
            ->whereRaw(order_query_sql('shipped'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        return $info;
    }

    /**
     * 添加商品标签
     *
     * @param  int  $id
     * @param  string  $tag
     * @return void
     */
    public static function add_tag($id, $tag)
    {
        if (empty($tag)) {
            return;
        }

        $arr = explode(',', $tag);

        foreach ($arr as $val) {
            // 检查是否重复
            $count = DB::table('user_tag')
                ->where('user_id', Session::get('user_id'))
                ->where('goods_id', $id)
                ->where('tag_words', $val)
                ->count();

            if ($count === 0) {
                DB::table('user_tag')->insert([
                    'user_id' => Session::get('user_id'),
                    'goods_id' => $id,
                    'tag_words' => $val,
                ]);
            }
        }
    }

    /**
     * 标签着色
     *
     * @param array
     * @return none
     */
    public static function color_tag(&$tags)
    {
        $tagmark = [
            ['color' => '#666666', 'size' => '0.8em', 'ifbold' => 1],
            ['color' => '#333333', 'size' => '0.9em', 'ifbold' => 0],
            ['color' => '#006699', 'size' => '1.0em', 'ifbold' => 1],
            ['color' => '#CC9900', 'size' => '1.1em', 'ifbold' => 0],
            ['color' => '#666633', 'size' => '1.2em', 'ifbold' => 1],
            ['color' => '#993300', 'size' => '1.3em', 'ifbold' => 0],
            ['color' => '#669933', 'size' => '1.4em', 'ifbold' => 1],
            ['color' => '#3366FF', 'size' => '1.5em', 'ifbold' => 0],
            ['color' => '#197B30', 'size' => '1.6em', 'ifbold' => 1],
        ];

        $maxlevel = count($tagmark);
        $tcount = $scount = [];

        foreach ($tags as $val) {
            $tcount[] = $val['tag_count']; // 获得tag个数数组
        }
        $tcount = array_unique($tcount); // 去除相同个数的tag

        sort($tcount); // 从小到大排序

        $tempcount = count($tcount); // 真正的tag级数
        $per = $maxlevel >= $tempcount ? 1 : $maxlevel / ($tempcount - 1);

        foreach ($tcount as $key => $val) {
            $lvl = floor($per * $key);
            $scount[$val] = $lvl; // 计算不同个数的tag相对应的着色数组key
        }

        $rewrite = intval(cfg('rewrite')) > 0;

        // 遍历所有标签，根据引用次数设定字体大小
        foreach ($tags as $key => $val) {
            $lvl = $scount[$val['tag_count']]; // 着色数组key

            $tags[$key]['color'] = $tagmark[$lvl]['color'];
            $tags[$key]['size'] = $tagmark[$lvl]['size'];
            $tags[$key]['bold'] = $tagmark[$lvl]['ifbold'];
            if ($rewrite) {
                if (strtolower(EC_CHARSET) !== 'utf-8') {
                    $tags[$key]['url'] = 'tag-'.urlencode(urlencode($val['tag_words'])).'.html';
                } else {
                    $tags[$key]['url'] = 'tag-'.urlencode($val['tag_words']).'.html';
                }
            } else {
                $tags[$key]['url'] = 'search.php?keywords='.urlencode($val['tag_words']);
            }
        }
        shuffle($tags);
    }

    /**
     * 取得用户等级信息
     *
     * @return array
     */
    public static function get_rank_info()
    {
        if (! empty(Session::get('user_rank'))) {
            $row = (array) DB::table('user_rank')
                ->select('rank_name', 'special_rank')
                ->where('rank_id', Session::get('user_rank'))
                ->first();
            if (empty($row)) {
                return [];
            }
            $rank_name = $row['rank_name'];
            if ($row['special_rank']) {
                return ['rank_name' => $rank_name];
            } else {
                $user_rank = DB::table('user')
                    ->where('user_id', Session::get('user_id'))
                    ->value('rank_points');

                $rt = (array) DB::table('user_rank')
                    ->select('rank_name', 'min_points')
                    ->where('min_points', '>', $user_rank)
                    ->orderBy('min_points')
                    ->first();
                $next_rank_name = $rt['rank_name'];
                $next_rank = $rt['min_points'] - $user_rank;

                return ['rank_name' => $rank_name, 'next_rank_name' => $next_rank_name, 'next_rank' => $next_rank];
            }
        } else {
            return [];
        }
    }

    /**
     *  获取用户参与活动信息
     *
     * @param  int  $user_id  用户id
     * @return array
     */
    public static function get_user_prompt($user_id)
    {
        $prompt = [];
        $now = TimeHelper::gmtime();
        // 夺宝奇兵
        $res = DB::table('goods_activity')
            ->select('act_id', 'goods_name', 'end_time')
            ->where('act_type', GAT_SNATCH)
            ->where(function ($query) use ($now) {
                $query->where('is_finished', 1)
                    ->orWhere(function ($query) use ($now) {
                        $query->where('is_finished', 0)->where('end_time', '<=', $now);
                    });
            })
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $row) {
            $act_id = $row['act_id'];
            $result = CommonHelper::get_snatch_result($act_id);
            if (isset($result['order_count']) && $result['order_count'] === 0 && $result['user_id'] === $user_id) {
                $prompt[] = [
                    'text' => sprintf(lang('your_snatch'), $row['goods_name'], $row['act_id']),
                    'add_time' => $row['end_time'],
                ];
            }
            if (isset($auction['last_bid']) && $auction['last_bid']['bid_user'] === $user_id && $auction['order_count'] === 0) {
                $prompt[] = [
                    'text' => sprintf(lang('your_auction'), $row['goods_name'], $row['act_id']),
                    'add_time' => $row['end_time'],
                ];
            }
        }

        // 竞拍

        $res = DB::table('goods_activity')
            ->select('act_id', 'goods_name', 'end_time')
            ->where('act_type', GAT_AUCTION)
            ->where(function ($query) use ($now) {
                $query->where('is_finished', 1)
                    ->orWhere(function ($query) use ($now) {
                        $query->where('is_finished', 0)->where('end_time', '<=', $now);
                    });
            })
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $row) {
            $act_id = $row['act_id'];
            $auction = GoodsHelper::auction_info($act_id);
            if (isset($auction['last_bid']) && $auction['last_bid']['bid_user'] === $user_id && $auction['order_count'] === 0) {
                $prompt[] = [
                    'text' => sprintf(lang('your_auction'), $row['goods_name'], $row['act_id']),
                    'add_time' => $row['end_time'],
                ];
            }
        }

        // 排序
        $cmp = function ($a, $b) {
            if ($a['add_time'] === $b['add_time']) {
                return 0;
            }

            return $a['add_time'] < $b['add_time'] ? 1 : -1;
        };

        usort($prompt, $cmp);

        // 格式化时间
        foreach ($prompt as $key => $val) {
            $prompt[$key]['formated_time'] = TimeHelper::local_date(cfg('time_format'), $val['add_time']);
        }

        return $prompt;
    }

    /**
     *  获取用户评论
     *
     * @param  int  $user_id  用户id
     * @param  int  $page_size  列表最大数量
     * @param  int  $start  列表起始页
     * @return array
     */
    public static function get_comment_list($user_id, $page_size, $start)
    {
        $res = DB::table('comment as c')
            ->select('c.*', 'g.goods_name as cmt_name', 'r.content as reply_content', 'r.add_time as reply_time')
            ->leftJoin('comment as r', function ($join) {
                $join->on('r.parent_id', '=', 'c.comment_id')
                    ->where('r.parent_id', '>', 0);
            })
            ->leftJoin('goods as g', function ($join) {
                $join->on('c.id_value', '=', 'g.goods_id')
                    ->where('c.comment_type', '=', 0);
            })
            ->where('c.user_id', $user_id)
            ->offset($start)
            ->limit($page_size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $comments = [];
        $to_article = [];
        foreach ($res as $row) {
            $row['formated_add_time'] = TimeHelper::local_date(cfg('time_format'), $row['add_time']);
            if ($row['reply_time']) {
                $row['formated_reply_time'] = TimeHelper::local_date(cfg('time_format'), $row['reply_time']);
            }
            if ($row['comment_type'] === 1) {
                $to_article[] = $row['id_value'];
            }
            $comments[] = $row;
        }

        if ($to_article) {
            $arr = DB::table('article')
                ->select('article_id', 'title')
                ->whereIn('article_id', array_unique($to_article))
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            $to_cmt_name = [];
            foreach ($arr as $row) {
                $to_cmt_name[$row['article_id']] = $row['title'];
            }

            foreach ($comments as $key => $row) {
                if ($row['comment_type'] === 1) {
                    $comments[$key]['cmt_name'] = isset($to_cmt_name[$row['id_value']]) ? $to_cmt_name[$row['id_value']] : '';
                }
            }
        }

        return $comments;
    }
}
