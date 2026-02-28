<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 载入语言文件
        require_once ROOT_PATH.'languages/'.cfg('lang').'/shopping_flow.php';
        require_once ROOT_PATH.'languages/'.cfg('lang').'/user.php';

        $this->assign_template();
        $this->assign_dynamic('activity');
        $position = $this->assign_ur_here(0, lang('shopping_activity'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        // 数据准备

        // 取得用户等级
        $user_rank_list = [];
        $user_rank_list[0] = lang('not_user');
        $res = DB::table('user_rank')->select('rank_id', 'rank_name')->get();
        foreach ($res as $row) {
            $user_rank_list[$row->rank_id] = $row->rank_name;
        }

        // 开始工作
        $res = DB::table('activity')->orderBy('sort_order')->orderByDesc('end_time')->get()->map(fn ($item) => (array) $item);

        $list = [];
        foreach ($res as $row) {
            $row['start_time'] = TimeHelper::local_date('Y-m-d H:i', $row['start_time']);
            $row['end_time'] = TimeHelper::local_date('Y-m-d H:i', $row['end_time']);

            // 享受优惠会员等级
            $user_rank = explode(',', $row['user_rank']);
            $row['user_rank'] = [];
            foreach ($user_rank as $val) {
                if (isset($user_rank_list[$val])) {
                    $row['user_rank'][] = $user_rank_list[$val];
                }
            }

            // 优惠范围类型、内容
            if ($row['act_range'] != FAR_ALL && ! empty($row['act_range_ext'])) {
                if ($row['act_range'] === FAR_CATEGORY) {
                    $row['act_range'] = lang('far_category');
                    $row['program'] = 'category.php?id=';
                    $act_range_ext = DB::table('goods_category')
                        ->select('cat_id as id', 'cat_name as name')
                        ->whereIn('cat_id', explode(',', $row['act_range_ext']))
                        ->get()
                        ->map(fn ($item) => (array) $item)
                        ->all();
                } elseif ($row['act_range'] === FAR_BRAND) {
                    $row['act_range'] = lang('far_brand');
                    $row['program'] = 'brand.php?id=';
                    $act_range_ext = DB::table('goods_brand')
                        ->select('brand_id as id', 'brand_name as name')
                        ->whereIn('brand_id', explode(',', $row['act_range_ext']))
                        ->get()
                        ->map(fn ($item) => (array) $item)
                        ->all();
                } else {
                    $row['act_range'] = lang('far_goods');
                    $row['program'] = 'goods.php?id=';
                    $act_range_ext = DB::table('goods')
                        ->select('goods_id as id', 'goods_name as name')
                        ->whereIn('goods_id', explode(',', $row['act_range_ext']))
                        ->get()
                        ->map(fn ($item) => (array) $item)
                        ->all();
                }
                $row['act_range_ext'] = $act_range_ext;
            } else {
                $row['act_range'] = lang('far_all');
            }

            // 优惠方式

            switch ($row['act_type']) {
                case 0:
                    $row['act_type'] = lang('fat_goods');
                    $row['gift'] = unserialize($row['gift']);
                    if (is_array($row['gift'])) {
                        foreach ($row['gift'] as $k => $v) {
                            $goods_thumb = DB::table('goods')->where('goods_id', $v['id'])->value('goods_thumb');
                            $row['gift'][$k]['thumb'] = CommonHelper::get_image_path($goods_thumb);
                        }
                    }
                    break;
                case 1:
                    $row['act_type'] = lang('fat_price');
                    $row['act_type_ext'] .= lang('unit_yuan');
                    $row['gift'] = [];
                    break;
                case 2:
                    $row['act_type'] = lang('fat_discount');
                    $row['act_type_ext'] .= '%';
                    $row['gift'] = [];
                    break;
            }

            $list[] = $row;
        }

        $this->assign('list', $list);

        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

        $this->assign('feed_url', (cfg('rewrite') === 1) ? 'feed-typeactivity.xml' : 'feed.php?type=activity'); // RSS URL

        return $this->display('activity');
    }
}
