<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GalleryController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 参数
        $_REQUEST['id'] = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0; // 商品编号
        $_REQUEST['img'] = isset($_REQUEST['img']) ? intval($_REQUEST['img']) : 0; // 图片编号

        // 获得商品名称
        $goods_name = DB::table('goods')
            ->where('goods_id', $_REQUEST['id'])
            ->value('goods_name');

        // 如果该商品不存在，返回首页
        if ($goods_name === null) {
            return response()->redirectTo('/');
        }

        // 获得所有的图片
        $img_list = DB::table('goods_gallery')
            ->select('img_id', 'img_desc', 'thumb_url', 'img_url')
            ->where('goods_id', $_REQUEST['id'])
            ->orderBy('img_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $img_count = count($img_list);

        $gallery = ['goods_name' => htmlspecialchars($goods_name, ENT_QUOTES), 'list' => []];
        if ($img_count === 0) {
            // 如果没有图片，返回商品详情页
            return response()->redirectTo('goods.php?id='.$_REQUEST['id']);
        } else {
            foreach ($img_list as $key => $img) {
                $gallery['list'][] = [
                    'gallery_thumb' => CommonHelper::get_image_path($img_list[$key]['thumb_url']),
                    'gallery' => CommonHelper::get_image_path($img_list[$key]['img_url']),
                    'img_desc' => $img_list[$key]['img_desc'],
                ];
            }
        }

        $this->assign('shop_name', cfg('shop_name'));
        $this->assign('watermark', str_replace('../', './', cfg('watermark')));
        $this->assign('gallery', $gallery);

        return $this->display('gallery');
    }
}
