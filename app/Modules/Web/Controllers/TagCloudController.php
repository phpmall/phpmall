<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\ClipsHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;

class TagCloudController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->assign_template();
        $position = $this->assign_ur_here(0, lang('tag_cloud'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置
        $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助
        $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行
        $this->assign('promotion_info', CommonHelper::get_promotion_info());

        // 调查
        $vote = MainHelper::get_vote();
        if (! empty($vote)) {
            $this->assign('vote_id', $vote['id']);
            $this->assign('vote', $vote['content']);
        }

        $this->assign_dynamic('tag_cloud');

        $tags = MainHelper::get_tags();

        if (! empty($tags)) {
            ClipsHelper::color_tag($tags);
        }

        $this->assign('tags', $tags);

        return $this->display('tag_cloud');
    }
}
