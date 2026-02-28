<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompareController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if (! empty($_REQUEST['goods']) && is_array($_REQUEST['goods']) && count($_REQUEST['goods']) > 1) {
            foreach ($_REQUEST['goods'] as $key => $val) {
                $_REQUEST['goods'][$key] = intval($val);
            }

            $cmt = DB::table('comment')
                ->select('id_value', DB::raw('AVG(comment_rank) AS cmt_rank'), DB::raw('COUNT(*) AS cmt_count'))
                ->whereRaw('comment_type = 0 AND id_value '.db_create_in($_REQUEST['goods']))
                ->groupBy('id_value')
                ->get()
                ->keyBy('id_value')
                ->map(fn ($item) => (array) $item)
                ->all();

            $res = DB::table('goods as g')
                ->select('g.goods_id', 'g.goods_type', 'g.goods_name', 'g.shop_price', 'g.goods_weight', 'g.goods_thumb', 'g.goods_brief', 'a.attr_name', 'v.attr_value', 'a.attr_id', 'b.brand_name', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS rank_price"))
                ->leftJoin('goods_attr as v', 'v.goods_id', '=', 'g.goods_id')
                ->leftJoin('goods_type_attribute as a', 'a.attr_id', '=', 'v.attr_id')
                ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                ->leftJoin('goods_member_price as mp', function ($join) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', Session::get('user_rank', 0));
                })
                ->where('g.is_delete', 0)
                ->whereRaw('g.goods_id '.db_create_in($_REQUEST['goods']))
                ->orderBy('a.attr_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            $arr = [];
            $ids = $_REQUEST['goods'];
            $attr_name = [];
            $type_id = 0;
            foreach ($res as $row) {
                $goods_id = $row['goods_id'];
                $type_id = $row['goods_type'];
                $arr[$goods_id]['goods_id'] = $goods_id;
                $arr[$goods_id]['url'] = build_uri('goods', ['gid' => $goods_id], $row['goods_name']);
                $arr[$goods_id]['goods_name'] = $row['goods_name'];
                $arr[$goods_id]['shop_price'] = CommonHelper::price_format($row['shop_price']);
                $arr[$goods_id]['rank_price'] = CommonHelper::price_format($row['rank_price']);
                $arr[$goods_id]['goods_weight'] = (intval($row['goods_weight']) > 0) ?
                    ceil($row['goods_weight']).lang('kilogram') : ceil($row['goods_weight'] * 1000).lang('gram');
                $arr[$goods_id]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
                $arr[$goods_id]['goods_brief'] = $row['goods_brief'];
                $arr[$goods_id]['brand_name'] = $row['brand_name'];

                $arr[$goods_id]['properties'][$row['attr_id']]['name'] = $row['attr_name'];
                if (! empty($arr[$goods_id]['properties'][$row['attr_id']]['value'])) {
                    $arr[$goods_id]['properties'][$row['attr_id']]['value'] .= ','.$row['attr_value'];
                } else {
                    $arr[$goods_id]['properties'][$row['attr_id']]['value'] = $row['attr_value'];
                }

                if (! isset($arr[$goods_id]['comment_rank'])) {
                    $arr[$goods_id]['comment_rank'] = isset($cmt[$goods_id]) ? ceil($cmt[$goods_id]['cmt_rank']) : 0;
                    $arr[$goods_id]['comment_number'] = isset($cmt[$goods_id]) ? $cmt[$goods_id]['cmt_count'] : 0;
                    $arr[$goods_id]['comment_number'] = sprintf(lang('comment_num'), $arr[$goods_id]['comment_number']);
                }

                $tmp = $ids;
                $key = array_search($goods_id, $tmp);

                if ($key !== null && $key !== false) {
                    unset($tmp[$key]);
                }

                $arr[$goods_id]['ids'] = ! empty($tmp) ? 'goods[]='.implode('&amp;goods[]=', $tmp) : '';
            }

            $attribute = DB::table('goods_type_attribute')
                ->select('attr_id', 'attr_name')
                ->where('cat_id', $type_id)
                ->orderBy('attr_id')
                ->pluck('attr_name', 'attr_id')
                ->all();

            $this->assign('attribute', $attribute);
            $this->assign('goods_list', $arr);
        } else {
            $this->show_message(lang('compare_no_goods'));
            exit;
        }

        $this->assign_template();
        $position = $this->assign_ur_here(0, lang('goods_compare'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

        $this->assign_dynamic('compare');

        return $this->display('compare');
    }
}
