<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QuotationController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $action = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';
        if ($action === 'print_quotation') {
            $smarty->template_dir = DATA_DIR;
            $this->assign('shop_name', cfg('shop_title'));
            $this->assign('cfg', cfg());
            $where = $this->get_quotation_where($_POST);
            $goods_list = DB::table('goods as g')
                ->select('g.goods_id', 'g.goods_name', 'g.shop_price', 'g.goods_number', 'c.cat_name as goods_category', 'p.product_id', 'p.product_number', 'p.goods_attr')
                ->leftJoin('goods_category as c', 'g.cat_id', '=', 'c.cat_id')
                ->leftJoin('goods_product as p', 'g.goods_id', '=', 'p.goods_id')
                ->whereRaw('1'.$where.' AND is_on_sale = 1 AND is_alone_sale = 1')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($goods_list as $key => $val) {
                if (! empty($val['product_id'])) {
                    $goods_list[$key]['goods_number'] = $val['product_number'];
                    $product_info = $this->product_info($val['goods_attr'], $val['goods_id']);
                    $goods_list[$key]['members_price'] = $val['shop_price'];
                    $goods_list[$key]['shop_price'] += $product_info['attr_price'];
                    $goods_list[$key]['product_name'] = $product_info['attr_value'];
                    $goods_list[$key]['attr_price'] = $product_info['attr_price'];
                } else {
                    $goods_list[$key]['members_price'] = $val['shop_price'];
                    $goods_list[$key]['product_name'] = '&nbsp;';
                    $goods_list[$key]['product_price'] = 0;
                }
                $goods_list[$key]['goods_key'] = $key;
            }
            $user_rank = DB::table('user_rank')
                ->where('show_price', 1)
                ->orWhere('rank_id', Session::get('user_rank'))
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            $rank_point = 0;
            if (Session::has('user_id')) {
                $rank_point = DB::table('user')
                    ->where('user_id', Session::get('user_id'))
                    ->value('rank_points');
            }
            $user_rank = $this->calc_user_rank($user_rank, $rank_point);
            $user_men = $this->serve_user($goods_list);
            $this->assign('extend_price', $user_rank['ext_price']);
            $this->assign('extend_rank', $user_men);
            $this->assign('goods_list', $goods_list);

            $html = $this->fetch('quotation_print.html');
            exit($html);
        }

        $this->assign_template();

        $position = $this->assign_ur_here(0, lang('quotation'));
        $this->assign('page_title', $position['title']);   // 页面标题
        $this->assign('ur_here', $position['ur_here']); // 当前位置

        $this->assign('cat_list', CommonHelper::cat_list());
        $this->assign('brand_list', CommonHelper::get_brand_list());

        if (is_null($smarty->get_template_vars('helps'))) {
            $this->assign('helps', MainHelper::get_shop_help()); // 网店帮助
        }

        return $this->display('quotation');
    }

    private function get_quotation_where($filter)
    {
        $_filter = new StdClass;
        $_filter->cat_id = $filter['cat_id'];
        $_filter->brand_id = $filter['brand_id'];
        $where = get_where_sql($_filter);
        $_filter->keyword = $filter['keyword'];
        $where .= isset($_filter->keyword) && trim($_filter->keyword) != '' ? " AND (g.goods_name LIKE '%".BaseHelper::mysql_like_quote($_filter->keyword)."%' OR g.goods_sn LIKE '%".BaseHelper::mysql_like_quote($_filter->keyword)."%' OR g.goods_id LIKE '%".BaseHelper::mysql_like_quote($_filter->keyword)."%') " : '';

        return $where;
    }

    private function calc_user_rank($rank, $rank_point)
    {
        $_tmprank = [];
        foreach ($rank as $_rank) {
            if ($_rank['show_price']) {
                $_tmprank['ext_price'][] = $_rank['rank_name'];
                $_tmprank['ext_rank'][] = $_rank['discount'];
            } else {
                if (Session::has('user_id') && ($rank_point >= $_rank['min_points'])) {
                    $_tmprank['ext_price'][] = $_rank['rank_name'];
                    $_tmprank['ext_rank'][] = $_rank['discount'];
                }
            }
        }

        return $_tmprank;
    }

    private function serve_user($goods_list)
    {
        foreach ($goods_list as $key => $all_list) {
            $goods_id = $all_list['goods_id'];
            $goods_key = $all_list['goods_key'];
            $price = $all_list['members_price'];
            $res = DB::table('user_rank as r')
                ->select('rank_id', DB::raw("IFNULL(mp.user_price, r.discount * $price / 100) AS price"), 'r.rank_name', 'r.discount')
                ->leftJoin('goods_member_price as mp', function ($join) use ($goods_id) {
                    $join->on('mp.user_rank', '=', 'r.rank_id')
                        ->where('mp.goods_id', '=', $goods_id);
                })
                ->where('r.show_price', 1)
                ->orWhere('r.rank_id', Session::get('user_rank'))
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                $arr[$row['rank_id']] = [
                    'rank_name' => htmlspecialchars($row['rank_name']),
                    'price' => CommonHelper::price_format($row['price'] + $all_list['attr_price']),
                ];
            }
            $arr_list[$goods_key] = $arr;
        }

        return $arr_list;
    }

    private function product_info($goods_attr, $goods_id)
    {
        $res = DB::table('goods_attr')
            ->select('attr_value', 'attr_price')
            ->where('goods_id', $goods_id)
            ->whereIn('goods_attr_id', explode('|', $goods_attr))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        $i = 1;
        $count = count($result);
        foreach ($result as $val) {
            $i === $count ? $f = '' : $f = '<br/>';
            $product_info['attr_value'] .= $val['attr_value'].$f;
            $product_info['attr_price'] += $val['attr_price'];
            $i++;
        }

        return $product_info;
    }
}
