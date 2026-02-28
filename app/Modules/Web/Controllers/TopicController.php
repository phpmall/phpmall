<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class TopicController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $topic_id = empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']);
        $now = TimeHelper::gmtime();
        $topic = (array) DB::table('activity_topic')
            ->where('topic_id', $topic_id)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->select('template')
            ->first();

        if (empty($topic)) {
            // 如果没有找到任何记录则跳回到首页
            return response()->redirectTo('/');
        }

        $templates = empty($topic['template']) ? 'topic' : $topic['template'];

        $cache_id = sprintf('%X', crc32(Session::get('user_rank').'-'.cfg('lang').'-'.$topic_id));

        if (! $this->is_cached($templates, $cache_id)) {
            $topic = (array) DB::table('activity_topic')
                ->where('topic_id', $topic_id)
                ->first();

            $topic['data'] = addcslashes($topic['data'], "'");
            $tmp = @unserialize($topic['data']);
            $arr = (array) $tmp;

            $goods_id = [];

            foreach ($arr as $key => $value) {
                foreach ($value as $k => $val) {
                    $opt = explode('|', $val);
                    $arr[$key][$k] = $opt[1];
                    $goods_id[] = $opt[1];
                }
            }

            $user_rank = Session::get('user_rank', 0);
            $discount = Session::get('discount', 1);

            $res = DB::table('goods as g')
                ->leftJoin('goods_member_price as mp', function ($join) use ($user_rank) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', $user_rank);
                })
                ->whereIn('g.goods_id', $goods_id)
                ->select(
                    'g.goods_id',
                    'g.goods_name',
                    'g.goods_name_style',
                    'g.market_price',
                    'g.is_new',
                    'g.is_best',
                    'g.is_hot',
                    'g.shop_price as org_price',
                    DB::raw("IFNULL(mp.user_price, g.shop_price * '".$discount."') as shop_price"),
                    'g.promote_price',
                    'g.promote_start_date',
                    'g.promote_end_date',
                    'g.goods_brief',
                    'g.goods_thumb',
                    'g.goods_img'
                )
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $sort_goods_arr = [];

            foreach ($res as $row) {
                if ($row['promote_price'] > 0) {
                    $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                    $row['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
                } else {
                    $row['promote_price'] = '';
                }

                if ($row['shop_price'] > 0) {
                    $row['shop_price'] = CommonHelper::price_format($row['shop_price']);
                } else {
                    $row['shop_price'] = '';
                }

                $row['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
                $row['goods_style_name'] = GoodsHelper::add_style($row['goods_name'], $row['goods_name_style']);
                $row['short_name'] = cfg('goods_name_length') > 0 ?
                    Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
                $row['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
                $row['short_style_name'] = GoodsHelper::add_style($row['short_name'], $row['goods_name_style']);

                foreach ($arr as $key => $value) {
                    foreach ($value as $val) {
                        if ($val === $row['goods_id']) {
                            $key = $key === 'default' ? lang('all_goods') : $key;
                            $sort_goods_arr[$key][] = $row;
                        }
                    }
                }
            }

            $this->assign_template();
            $position = $this->assign_ur_here();
            $this->assign('page_title', $position['title']);       // 页面标题
            $this->assign('ur_here', $position['ur_here'].'> '.$topic['title']);     // 当前位置
            $this->assign('show_marketprice', cfg('show_marketprice'));
            $this->assign('sort_goods_arr', $sort_goods_arr);          // 商品列表
            $this->assign('topic', $topic);                   // 专题信息
            $this->assign('keywords', $topic['keywords']);       // 专题信息
            $this->assign('description', $topic['description']);    // 专题信息
            $this->assign('title_pic', $topic['title_pic']);      // 分类标题图片地址
            $this->assign('base_style', '#'.$topic['base_style']);     // 基本风格样式颜色

            $template_file = empty($topic['template']) ? 'topic' : $topic['template'];
        }

        return $this->display($templates, $cache_id);
    }
}
