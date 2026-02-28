<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 用户推荐页面
        if ($action === 'affiliate') {
            $goodsid = intval(isset($_REQUEST['goodsid']) ? $_REQUEST['goodsid'] : 0);
            if (empty($goodsid)) {
                // 我的推荐页面

                $page = ! empty($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
                $size = ! empty(cfg('page_size')) && intval(cfg('page_size')) > 0 ? intval(cfg('page_size')) : 10;

                empty($affiliate) && $affiliate = [];

                if (empty($affiliate['config']['separate_by'])) {
                    // 推荐注册分成
                    $affdb = [];
                    $num = count($affiliate['item']);
                    $up_uid = [$this->getUserId()];
                    $all_uid = [$this->getUserId()];
                    for ($i = 1; $i <= $num; $i++) {
                        $count = 0;
                        if (! empty($up_uid)) {
                            $current_ids = DB::table('users')->whereIn('parent_id', $up_uid)->pluck('user_id')->toArray();
                            $up_uid = $current_ids;
                            if ($i < $num) {
                                $all_uid = array_merge($all_uid, $current_ids);
                            }
                            $count = count($current_ids);
                        }
                        $affdb[$i]['num'] = $count;
                        $affdb[$i]['point'] = $affiliate['item'][$i - 1]['level_point'];
                        $affdb[$i]['money'] = $affiliate['item'][$i - 1]['level_money'];
                    }
                    $this->assign('affdb', $affdb);

                    $query = DB::table('order_info as o')
                        ->leftJoin('users as u', 'o.user_id', '=', 'u.user_id')
                        ->leftJoin('user_affiliate as a', 'o.order_id', '=', 'a.order_id')
                        ->where('o.user_id', '>', 0)
                        ->where(function ($q) use ($all_uid) {
                            $q->where(function ($q2) use ($all_uid) {
                                $q2->whereIn('u.parent_id', $all_uid)->where('o.is_separate', 0);
                            })->orWhere(function ($q2) {
                                $q2->where('a.user_id', $this->getUserId())->where('o.is_separate', '>', 0);
                            });
                        });

                    /*
                        SQL解释：

                        订单、用户、分成记录关联
                        一个订单可能有多个分成记录

                        1、订单有效 o.user_id > 0
                        2、满足以下之一：
                            a.直接下线的未分成订单 u.parent_id IN ($all_uid) AND o.is_separate = 0
                                其中$all_uid为该ID及其下线(不包含最后一层下线)
                            b.全部已分成订单 a.user_id = '$this->getUserId()' AND o.is_separate > 0

                    */

                    $affiliate_intro = nl2br(sprintf(lang('affiliate_intro')[$affiliate['config']['separate_by']], $affiliate['config']['expire'], lang('expire_unit')[$affiliate['config']['expire_unit']], $affiliate['config']['level_register_all'], $affiliate['config']['level_register_up'], $affiliate['config']['level_money_all'], $affiliate['config']['level_point_all']));
                } else {
                    // 推荐订单分成
                    $query = DB::table('order_info as o')
                        ->leftJoin('users as u', 'o.user_id', '=', 'u.user_id')
                        ->leftJoin('user_affiliate as a', 'o.order_id', '=', 'a.order_id')
                        ->where('o.user_id', '>', 0)
                        ->where(function ($q) {
                            $q->where(function ($q2) {
                                $q2->where('o.parent_id', $this->getUserId())->where('o.is_separate', 0);
                            })->orWhere(function ($q2) {
                                $q2->where('a.user_id', $this->getUserId())->where('o.is_separate', '>', 0);
                            });
                        });

                    /*
                        SQL解释：

                        订单、用户、分成记录关联
                        一个订单可能有多个分成记录

                        1、订单有效 o.user_id > 0
                        2、满足以下之一：
                            a.订单下线的未分成订单 o.parent_id = '$this->getUserId()' AND o.is_separate = 0
                            b.全部已分成订单 a.user_id = '$this->getUserId()' AND o.is_separate > 0

                    */

                    $affiliate_intro = nl2br(sprintf(lang('affiliate_intro')[$affiliate['config']['separate_by']], $affiliate['config']['expire'], lang('expire_unit')[$affiliate['config']['expire_unit']], $affiliate['config']['level_money_all'], $affiliate['config']['level_point_all']));
                }

                $count = $query->count();

                $max_page = ($count > 0) ? ceil($count / $size) : 1;
                if ($page > $max_page) {
                    $page = $max_page;
                }

                $res = $query->select('o.*', 'a.log_id', 'a.user_id as suid', 'a.user_name as auser', 'a.money', 'a.point', 'a.separate_type')
                    ->orderByDesc('o.order_id')
                    ->offset(($page - 1) * $size)
                    ->limit($size)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                $logdb = [];
                foreach ($res as $rt) {
                    if (! empty($rt['suid'])) {
                        // 在affiliate_log有记录
                        if ($rt['separate_type'] === -1 || $rt['separate_type'] === -2) {
                            // 已被撤销
                            $rt['is_separate'] = 3;
                        }
                    }
                    $rt['order_sn'] = substr($rt['order_sn'], 0, strlen($rt['order_sn']) - 5).'***'.substr($rt['order_sn'], -2, 2);
                    $logdb[] = $rt;
                }

                $url_format = 'user.php?act=affiliate&page=';

                $pager = [
                    'page' => $page,
                    'size' => $size,
                    'sort' => '',
                    'order' => '',
                    'record_count' => $count,
                    'page_count' => $max_page,
                    'page_first' => $url_format.'1',
                    'page_prev' => $page > 1 ? $url_format.($page - 1) : 'javascript:;',
                    'page_next' => $page < $max_page ? $url_format.($page + 1) : 'javascript:;',
                    'page_last' => $url_format.$max_page,
                    'array' => [],
                ];
                for ($i = 1; $i <= $max_page; $i++) {
                    $pager['array'][$i] = $i;
                }

                $this->assign('url_format', $url_format);
                $this->assign('pager', $pager);

                $this->assign('affiliate_intro', $affiliate_intro);
                $this->assign('affiliate_type', $affiliate['config']['separate_by']);

                $this->assign('logdb', $logdb);
            } else {
                // 单个商品推荐
                $this->assign('userid', $this->getUserId());
                $this->assign('goodsid', $goodsid);

                $types = [1, 2, 3, 4, 5];
                $this->assign('types', $types);

                $goods = GoodsHelper::get_goods_info($goodsid);
                $shopurl = ecs()->url();
                $goods['goods_img'] = (strpos($goods['goods_img'], 'http://') === false && strpos($goods['goods_img'], 'https://') === false) ? $shopurl.$goods['goods_img'] : $goods['goods_img'];
                $goods['goods_thumb'] = (strpos($goods['goods_thumb'], 'http://') === false && strpos($goods['goods_thumb'], 'https://') === false) ? $shopurl.$goods['goods_thumb'] : $goods['goods_thumb'];
                $goods['shop_price'] = CommonHelper::price_format($goods['shop_price']);

                $this->assign('goods', $goods);
            }

            $this->assign('shopname', cfg('shop_name'));
            $this->assign('userid', $this->getUserId());
            $this->assign('shopurl', ecs()->url());
            $this->assign('logosrc', 'themes/'.cfg('template').'/images/logo.gif');

            return $this->display('user_clips');
        }
    }
}
