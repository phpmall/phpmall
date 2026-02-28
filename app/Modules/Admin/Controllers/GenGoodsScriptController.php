<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;

class GenGoodsScriptController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 生成代码
         */
        if ($action === 'setup') {
            $this->admin_priv('gen_goods_script');

            // 编码
            $lang_list = [
                'UTF8' => lang('charset.utf8'),
                'GB2312' => lang('charset.zh_cn'),
                'BIG5' => lang('charset.zh_tw'),
            ];

            // 参数赋值
            $ur_here = lang('16_goods_script');
            $this->assign('ur_here', $ur_here);
            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('intro_list', lang('intro'));
            $this->assign('url', ecs()->url());
            $this->assign('lang_list', $lang_list);

            return $this->display('gen_goods_script');
        }
    }
}
