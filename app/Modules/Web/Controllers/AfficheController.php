<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AfficheController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        define('INIT_NO_SMARTY', true);
        // 没有指定广告的id及跳转地址
        if (empty($_GET['ad_id'])) {
            return response()->redirectTo('index.php');
        } else {
            $ad_id = intval($_GET['ad_id']);
        }

        // act 操作项的初始化
        $_GET['act'] = ! empty($_GET['act']) ? trim($_GET['act']) : '';

        if ($_GET['act'] === 'js') {
            // 编码转换
            if (empty($_GET['charset'])) {
                $_GET['charset'] = 'UTF8';
            }

            header('Content-type: application/x-javascript; charset='.($_GET['charset'] === 'UTF8' ? 'utf-8' : $_GET['charset']));

            $url = ecs()->url();
            $str = '';

            $now = TimeHelper::gmtime();
            $ad_info = DB::table('ad as ad')
                ->select('ad.ad_id', 'ad.ad_name', 'ad.ad_link', 'ad.ad_code')
                ->leftJoin('ad_position as p', 'ad.position_id', '=', 'p.position_id')
                ->where('ad.ad_id', $ad_id)
                ->where('ad.start_time', '<=', $now)
                ->where('ad.end_time', '>=', $now)
                ->first();
            $ad_info = (array) $ad_info;

            if (! empty($ad_info)) {
                // 转换编码
                if ($_GET['charset'] != 'UTF8') {
                    $ad_info['ad_name'] = BaseHelper::ecs_iconv('UTF8', $_GET['charset'], $ad_info['ad_name']);
                    $ad_info['ad_code'] = BaseHelper::ecs_iconv('UTF8', $_GET['charset'], $ad_info['ad_code']);
                }

                // 初始化广告的类型和来源
                $_GET['type'] = ! empty($_GET['type']) ? intval($_GET['type']) : 0;
                $_GET['from'] = ! empty($_GET['from']) ? urlencode($_GET['from']) : '';

                $str = '';
                switch ($_GET['type']) {
                    case '0':
                        // 图片广告
                        $src = (strpos($ad_info['ad_code'], 'http://') === false && strpos($ad_info['ad_code'], 'https://') === false) ? $url.DATA_DIR."/afficheimg/$ad_info[ad_code]" : $ad_info['ad_code'];
                        $str = '<a href="'.$url.'affiche.php?ad_id='.$ad_info['ad_id'].'&from='.$_GET['from'].'&uri='.urlencode($ad_info['ad_link']).'" target="_blank">'.
                            '<img src="'.$src.'" border="0" alt="'.$ad_info['ad_name'].'" /></a>';
                        break;

                    case '1':
                        // Falsh广告
                        $src = (strpos($ad_info['ad_code'], 'http://') === false && strpos($ad_info['ad_code'], 'https://') === false) ? $url.DATA_DIR.'/afficheimg/'.$ad_info['ad_code'] : $ad_info['ad_code'];
                        $str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0"> <param name="movie" value="'.$src.'"><param name="quality" value="high"><embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></object>';
                        break;

                    case '2':
                        // 代码广告
                        $str = $ad_info['ad_code'];
                        break;

                    case 3:
                        // 文字广告
                        $str = '<a href="'.$url.'affiche.php?ad_id='.$ad_info['ad_id'].'&from='.$_GET['from'].'&uri='.urlencode($ad_info['ad_link']).'" target="_blank">'.nl2br(htmlspecialchars(addslashes($ad_info['ad_code']))).'</a>';
                        break;
                }
            }
            echo "document.writeln('$str');";
        } else {
            // 获取投放站点的名称

            $site_name = ! empty($_GET['from']) ? htmlspecialchars($_GET['from']) : addslashes(lang('self_site'));

            // 商品的ID
            $goods_id = ! empty($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;

            // 存入SESSION中,购物后一起存到订单数据表里
            Session::put('from_ad', $ad_id);
            Session::put('referer', stripslashes($site_name));

            // 如果是商品的站外JS
            if ((string) $ad_id === '-1') {
                DB::table('ad_adsense')->updateOrInsert(
                    ['from_ad' => '-1', 'referer' => $site_name],
                    ['clicks' => DB::raw('clicks + 1')]
                );

                $goods_name = DB::table('goods')->where('goods_id', $goods_id)->value('goods_name');

                $uri = build_uri('goods', ['gid' => $goods_id], $goods_name);

                return response()->redirectTo($uri);
            } else {
                // 更新站内广告的点击次数
                DB::table('ad')->where('ad_id', $ad_id)->increment('click_count');

                DB::table('ad_adsense')->updateOrInsert(
                    ['from_ad' => (string) $ad_id, 'referer' => $site_name],
                    ['clicks' => DB::raw('clicks + 1')]
                );

                $ad_info = DB::table('ad')->where('ad_id', $ad_id)->first();
                $ad_info = (array) $ad_info;
                // 跳转到广告的链接页面
                if (! empty($ad_info['ad_link'])) {
                    $uri = (strpos($ad_info['ad_link'], 'http://') === false && strpos($ad_info['ad_link'], 'https://') === false) ? ecs()->http().urldecode($ad_info['ad_link']) : urldecode($ad_info['ad_link']);
                } else {
                    $uri = ecs()->url();
                }

                return response()->redirectTo($uri);
            }
        }
    }
}
