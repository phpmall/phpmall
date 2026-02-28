<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PickOutController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $condition = [];
        $picks = [];
        $cat_id = ! empty($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
        if (! empty($_GET['attr'])) {
            foreach ($_GET['attr'] as $key => $value) {
                if (! is_numeric($key)) {
                    unset($_GET['attr'][$key]);

                    continue;
                }
                $key = intval($key);
                $_GET['attr'][$key] = htmlspecialchars($value);
            }
        }

        if (empty($cat_id)) {
            // 获取所有符合条件的商品类型
            $rs = DB::table('goods_type as t')
                ->join('goods_type_attribute as a', 't.cat_id', '=', 'a.cat_id')
                ->join('goods_attr as g', 'a.attr_id', '=', 'g.attr_id')
                ->where('t.enabled', 1)
                ->select('t.cat_id', 't.cat_name')
                ->distinct()
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $in_cat = array_column($rs, 'cat_id');
            foreach ($rs as $row) {
                $condition[$row['cat_id']]['name'] = $row['cat_name'];
            }

            // 获取符合条件的属性
            $in_attr = DB::table('goods_attr as g')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
                ->whereIn('a.cat_id', $in_cat)
                ->distinct()
                ->pluck('a.attr_id')
                ->all();

            // 获取所有属性值
            $rs = DB::table('goods_attr as g')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
                ->whereIn('g.attr_id', $in_attr)
                ->select('g.attr_id', 'a.attr_name', 'a.cat_id', 'g.attr_value')
                ->distinct()
                ->orderBy('cat_id')
                ->get()
                ->toArray();

            foreach ($rs as $row) {
                if (empty($condition[$row['cat_id']]['cat'][$row['attr_id']]['cat_name'])) {
                    $condition[$row['cat_id']]['cat'][$row['attr_id']]['cat_name'] = $row['attr_name'];
                }

                $condition[$row['cat_id']]['cat'][$row['attr_id']]['list'][] = ['name' => $row['attr_value'], 'url' => 'pick_out.php?cat_id='.$row['cat_id'].'&amp;attr['.$row['attr_id'].']='.urlencode($row['attr_value'])];
            }

            // 获取商品总数
            $goods_count = DB::table('goods_attr')->distinct()->count('goods_id');
            // 获取符合条件的商品id
            $in_goods_ids = DB::table('goods_attr')->distinct()->pluck('goods_id')->all();
            $in_goods = $in_goods_ids;
            $url = 'search.php?pickout=1';
        } else {
            // 取得商品类型名称
            $cat_name = DB::table('goods_type')->where('cat_id', $cat_id)->value('cat_name');
            $condition[0]['name'] = $cat_name;

            $picks[] = ['name' => '<strong>'.lang('goods_type').':</strong><br />'.$cat_name, 'url' => 'pick_out.php'];

            $attr_picks = []; // 选择过的attr_id

            // 处理属性,获取满足属性的goods_id
            if (! empty($_GET['attr'])) {
                $attr_table = '';
                $attr_where = '';
                $attr_url = '';
                $i = 0;
                $goods_result = '';
                foreach ($_GET['attr'] as $key => $value) {
                    $attr_url .= '&attr['.$key.']='.$value;

                    $attr_picks[] = $key;
                    if ($i > 0) {
                        if (empty($goods_result)) {
                            break;
                        }
                        $goods_result = DB::table('goods_attr')
                            ->whereIn('goods_id', $goods_result)
                            ->where('attr_id', $key)
                            ->where('attr_value', $value)
                            ->pluck('goods_id')
                            ->all();
                    } else {
                        $goods_result = DB::table('goods_attr')
                            ->where('attr_id', $key)
                            ->where('attr_value', $value)
                            ->pluck('goods_id')
                            ->all();
                    }
                    $i++;
                }

                // 获取指定attr_id的名字
                $rs = DB::table('goods_type_attribute')
                    ->whereIn('attr_id', $attr_picks)
                    ->select('attr_id', 'attr_name')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                foreach ($rs as $row) {
                    $picks[] = ['name' => '<strong>'.$row['attr_name'].':</strong><br />'.htmlspecialchars(urldecode($_GET['attr'][$row['attr_id']])), 'url' => 'pick_out.php?cat_id='.$cat_id.$this->search_url($attr_picks, $row['attr_id'])];
                }

                // 查出数量
                $goods_count = count($goods_result);
                // 获取符合条件的goods_id
                $in_goods = $goods_result;
            } else {
                // 查出数量
                $goods_count = DB::table('goods_attr as g')
                    ->join('goods_type_attribute as a', 'g.attr_id', '=', 'a.attr_id')
                    ->where('a.cat_id', $cat_id)
                    ->distinct()
                    ->count('g.goods_id');

                // 防止结果过大，最多只查出前100个goods_id
                $goods_result = DB::table('goods_attr as g')
                    ->join('goods_type_attribute as a', 'g.attr_id', '=', 'a.attr_id')
                    ->where('a.cat_id', $cat_id)
                    ->distinct()
                    ->limit(100)
                    ->pluck('g.goods_id')
                    ->all();
                $in_goods = $goods_result;
            }

            // 获取符合条件的属性
            $in_attr = DB::table('goods_attr as g')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
                ->whereIn('g.goods_id', $in_goods)
                ->distinct()
                ->pluck('a.attr_id')
                ->all();
            $in_attr = array_diff($in_attr, $attr_picks); // 除去已经选择过的attr_id

            // 获取所有属性值
            $rs = DB::table('goods_attr as g')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
                ->whereIn('g.attr_id', $in_attr)
                ->whereIn('g.goods_id', $in_goods)
                ->select('g.attr_id', 'a.attr_name', 'g.attr_value')
                ->distinct()
                ->get()
                ->toArray();

            foreach ($rs as $row) {
                if (empty($condition[0]['cat'][$row['attr_id']]['cat_name'])) {
                    $condition[0]['cat'][$row['attr_id']]['cat_name'] = $row['attr_name'];
                }
                $condition[0]['cat'][$row['attr_id']]['list'][] = ['name' => $row['attr_value'], 'url' => 'pick_out.php?cat_id='.$cat_id.$this->search_url($attr_picks).'&amp;attr['.$row['attr_id'].']='.urlencode($row['attr_value'])];
            }

            // 生成更多商品的url
            $url = 'search.php?pickout=1&amp;cat_id='.$cat_id.$this->search_url($attr_picks);
        }

        $goods = [];
        $query = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.market_price', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS shop_price"), 'g.promote_price', 'promote_start_date', 'promote_end_date', 'g.goods_brief', 'g.goods_thumb')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank', 0));
            })
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->whereIn('g.goods_id', $in_goods)
            ->orderBy('g.sort_order')
            ->orderByDesc('g.last_update')
            ->limit(4);

        $res = $query->get()->toArray();

        // 获取品牌
        $brand_list = DB::table('goods as g')
            ->select('b.brand_id', 'b.brand_name', 'b.brand_logo', DB::raw('COUNT(g.goods_id) AS goods_num'))
            ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->where('b.brand_id', '>', 0)
            ->whereIn('g.goods_id', $in_goods)
            ->groupBy('g.brand_id')
            ->get()
            ->toArray();

        foreach ($brand_list as $key => $val) {
            $brand_list[$key]['url'] = $url.'&amp;brand='.$val['brand_id'];
        }

        // 获取分类
        $cat_list = DB::table('goods as g')
            ->select('c.cat_id', 'c.cat_name', DB::raw('COUNT(g.goods_id) AS goods_num'))
            ->leftJoin('goods_category as c', 'c.cat_id', '=', 'g.cat_id')
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->whereIn('g.goods_id', $in_goods)
            ->groupBy('g.cat_id')
            ->get()
            ->toArray();

        foreach ($cat_list as $key => $val) {
            $cat_list[$key]['url'] = $url.'&amp;category='.$val['cat_id'];
        }

        $idx = 0;
        foreach ($res as $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }

            $goods[$idx]['id'] = $row['goods_id'];
            $goods[$idx]['name'] = $row['goods_name'];
            $goods[$idx]['short_name'] = cfg('goods_name_length') > 0 ? Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            $goods[$idx]['market_price'] = $row['market_price'];
            $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
            $goods[$idx]['brief'] = $row['goods_brief'];
            $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            $idx++;
        }

        $picks[] = ['name' => lang('remove_all'), 'url' => 'pick_out.php'];

        $this->assign_template();
        $position = $this->assign_ur_here(0, lang('pick_out'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        $this->assign('brand_list', $brand_list);       // 品牌
        $this->assign('cat_list', $cat_list);        // 分类列表

        $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
        $this->assign('helps', MainHelper::get_shop_help());  // 网店帮助
        $this->assign('top_goods', GoodsHelper::get_top10());      // 销售排行
        $this->assign('data_dir', DATA_DIR);  // 数据目录

        // 调查
        $vote = MainHelper::get_vote();
        if (! empty($vote)) {
            $this->assign('vote_id', $vote['id']);
            $this->assign('vote', $vote['content']);
        }

        $this->assign_dynamic('pick_out');

        $this->assign('url', $url);
        $this->assign('pickout_goods', $goods);
        $this->assign('count', $goods_count);
        $this->assign('picks', $picks);
        $this->assign('condition', $condition);

        return $this->display('pick_out');
    }

    /**
     *  生成搜索的链接地址
     *
     * @param int        attr_id        要排除的attr_id
     * @return string
     */
    private function search_url(&$attr_picks, $attr_id = 0)
    {
        $str = '';
        foreach ($attr_picks as $pick_id) {
            if ($pick_id != $attr_id) {
                $str .= '&amp;attr['.$pick_id.']='.urlencode($_GET['attr'][$pick_id]);
            }
        }

        return $str;
    }
}
