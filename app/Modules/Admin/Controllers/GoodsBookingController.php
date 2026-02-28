<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class GoodsBookingController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('booking');
        /**
         * 列出所有订购信息
         */
        if ($action === 'list_all') {
            $this->assign('ur_here', lang('list_all'));
            $this->assign('full_page', 1);

            $list = $this->get_bookinglist();

            $this->assign('booking_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('booking_list');
        }

        /**
         * 翻页、排序
         */
        if ($action === 'query') {
            $list = $this->get_bookinglist();

            $this->assign('booking_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('booking_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 删除缺货登记
         */
        if ($action === 'remove') {
            $this->check_authz_json('booking');

            $id = intval($_GET['id']);

            DB::table('user_booking')
                ->where('rec_id', $id)
                ->delete();

            $url = 'goods_booking.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 显示详情
         */
        if ($action === 'detail') {
            $id = intval($_REQUEST['id']);

            $this->assign('send_fail', ! empty($_REQUEST['send_ok']));
            $this->assign('booking', $this->get_booking_info($id));
            $this->assign('ur_here', lang('detail'));
            $this->assign('action_link', ['text' => lang('06_undispose_booking'), 'href' => 'goods_booking.php?act=list_all']);

            return $this->display('booking_info');
        }

        /**
         * 处理提交数据
         */
        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('booking');

            $dispose_note = ! empty($_POST['dispose_note']) ? trim($_POST['dispose_note']) : '';

            DB::table('user_booking')
                ->where('rec_id', $_REQUEST['rec_id'])
                ->update([
                    'is_dispose' => 1,
                    'dispose_note' => $dispose_note,
                    'dispose_time' => TimeHelper::gmtime(),
                    'dispose_user' => Session::get('admin_name'),
                ]);

            // 邮件通知处理流程
            if (! empty($_POST['send_email_notice']) or isset($_POST['remail'])) {
                // 获取邮件中的必要内容
                $booking_info = DB::table('user_booking as bg')
                    ->join('goods as g', 'bg.goods_id', '=', 'g.goods_id')
                    ->select('bg.email', 'bg.link_man', 'bg.goods_id', 'g.goods_name')
                    ->where('bg.rec_id', $_REQUEST['rec_id'])
                    ->first();
                $booking_info = (array) $booking_info;

                // 设置缺货回复模板所需要的内容信息
                $template = CommonHelper::get_mail_template('goods_booking');
                $goods_link = ecs()->url().'goods.php?id='.$booking_info['goods_id'];

                $this->assign('user_name', $booking_info['link_man']);
                $this->assign('goods_link', $goods_link);
                $this->assign('goods_name', $booking_info['goods_name']);
                $this->assign('dispose_note', $dispose_note);
                $this->assign('shop_name', "<a href='".ecs()->url()."'>".cfg('shop_name').'</a>');
                $this->assign('send_date', date('Y-m-d'));

                $content = $this->fetch('str:'.$template['template_content']);

                // 发送邮件
                if (BaseHelper::send_mail($booking_info['link_man'], $booking_info['email'], $template['template_subject'], $content, $template['is_html'])) {
                    $send_ok = 0;
                } else {
                    $send_ok = 1;
                }
            }

            return response()->redirectTo('?act=detail&id='.$_REQUEST['rec_id']."&send_ok=$send_ok");
        }
    }

    /**
     * 获取订购信息
     *
     *
     * @return array
     */
    private function get_bookinglist()
    {
        // 查询条件
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
            $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
        }
        $filter['dispose'] = empty($_REQUEST['dispose']) ? 0 : intval($_REQUEST['dispose']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'sort_order' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = (! empty($_REQUEST['keywords'])) ? " AND g.goods_name LIKE '%".BaseHelper::mysql_like_quote($filter['keywords'])."%' " : '';
        $where .= (! empty($_REQUEST['dispose'])) ? " AND bg.is_dispose = '$filter[dispose]' " : '';

        $filter['record_count'] = DB::table('user_booking as bg')
            ->join('goods as g', 'bg.goods_id', '=', 'g.goods_id')
            ->where(function ($query) use ($filter) {
                if ($filter['keywords']) {
                    $query->where('g.goods_name', 'LIKE', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%');
                }
                if ($filter['dispose']) {
                    $query->where('bg.is_dispose', $filter['dispose']);
                }
            })
            ->count();

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $row = DB::table('user_booking as bg')
            ->join('goods as g', 'bg.goods_id', '=', 'g.goods_id')
            ->select('bg.rec_id', 'bg.link_man', 'g.goods_id', 'g.goods_name', 'bg.goods_number', 'bg.booking_time', 'bg.is_dispose')
            ->where(function ($query) use ($filter) {
                if ($filter['keywords']) {
                    $query->where('g.goods_name', 'LIKE', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%');
                }
                if ($filter['dispose']) {
                    $query->where('bg.is_dispose', $filter['dispose']);
                }
            })
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        foreach ($row as $key => $val) {
            $row[$key]['booking_time'] = TimeHelper::local_date(cfg('time_format'), $val['booking_time']);
        }
        $filter['keywords'] = stripslashes($filter['keywords']);
        $arr = ['item' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 获得缺货登记的详细信息
     *
     * @param  int  $id
     * @return array
     */
    private function get_booking_info($id)
    {
        $res = DB::table('user_booking as bg')
            ->leftJoin('goods as g', 'bg.goods_id', '=', 'g.goods_id')
            ->leftJoin('user as u', 'bg.user_id', '=', 'u.user_id')
            ->select(
                'bg.rec_id',
                'bg.user_id',
                DB::raw("IFNULL(u.user_name, '".lang('guest_user')."') AS user_name"),
                'bg.link_man',
                'g.goods_name',
                'bg.goods_id',
                'bg.goods_number',
                'bg.booking_time',
                'bg.goods_desc',
                'bg.dispose_user',
                'bg.dispose_time',
                'bg.email',
                'bg.tel',
                'bg.dispose_note',
                'bg.dispose_user',
                'bg.dispose_time',
                'bg.is_dispose'
            )
            ->where('bg.rec_id', $id)
            ->first();
        $res = (array) $res;

        // 格式化时间
        $res['booking_time'] = TimeHelper::local_date(cfg('time_format'), $res['booking_time']);
        if (! empty($res['dispose_time'])) {
            $res['dispose_time'] = TimeHelper::local_date(cfg('time_format'), $res['dispose_time']);
        }

        return $res;
    }
}
