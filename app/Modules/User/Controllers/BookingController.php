<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\ClipsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'booking_list') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            // 获取缺货登记的数量
            $record_count = DB::table('user_booking as bg')
                ->join('goods as g', 'bg.goods_id', '=', 'g.goods_id')
                ->where('bg.user_id', $this->getUserId())
                ->count();
            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);

            $this->assign('booking_list', ClipsHelper::get_booking_list($this->getUserId(), $pager['size'], $pager['start']));
            $this->assign('pager', $pager);

            return $this->display('user_clips');
        }

        // 添加缺货登记页面
        if ($action === 'add_booking') {
            $goods_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($goods_id === 0) {
                $this->show_message(lang('no_goods_id'), lang('back_page_up'), '', 'error');
            }

            // 根据规格属性获取货品规格信息
            $goods_attr = '';
            if ($_GET['spec'] != '') {
                $goods_attr_id = $_GET['spec'];

                $attr_list = [];
                $res = DB::table('goods_attr as g')
                    ->join('goods_type_attribute as a', 'g.attr_id', '=', 'a.attr_id')
                    ->select('a.attr_name', 'g.attr_value')
                    ->whereIn('g.goods_attr_id', is_array($goods_attr_id) ? $goods_attr_id : explode(',', $goods_attr_id))
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                foreach ($res as $row) {
                    $attr_list[] = $row['attr_name'].': '.$row['attr_value'];
                }
                $goods_attr = implode(chr(13).chr(10), $attr_list);
            }
            $this->assign('goods_attr', $goods_attr);

            $this->assign('info', ClipsHelper::get_goodsinfo($goods_id));

            return $this->display('user_clips');
        }

        // 添加缺货登记的处理
        if ($action === 'act_add_booking') {
            $booking = [
                'goods_id' => isset($_POST['id']) ? intval($_POST['id']) : 0,
                'goods_amount' => isset($_POST['number']) ? intval($_POST['number']) : 0,
                'desc' => isset($_POST['desc']) ? trim($_POST['desc']) : '',
                'linkman' => isset($_POST['linkman']) ? trim($_POST['linkman']) : '',
                'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
                'tel' => isset($_POST['tel']) ? trim($_POST['tel']) : '',
                'booking_id' => isset($_POST['rec_id']) ? intval($_POST['rec_id']) : 0,
            ];

            // 查看此商品是否已经登记过
            $rec_id = ClipsHelper::get_booking_rec($this->getUserId(), $booking['goods_id']);
            if ($rec_id > 0) {
                $this->show_message(lang('booking_rec_exist'), lang('back_page_up'), '', 'error');
            }

            if (ClipsHelper::add_booking($booking)) {
                $this->show_message(
                    lang('booking_success'),
                    lang('back_booking_list'),
                    'user.php?act=booking_list',
                    'info'
                );
            } else {
                $err->show(lang('booking_list_lnk'), 'user.php?act=booking_list');
            }
        }

        // 删除缺货登记
        if ($action === 'act_del_booking') {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id === 0 || $this->getUserId() === 0) {
                return response()->redirectTo('user.php?act=booking_list');
            }

            $result = ClipsHelper::delete_booking($id, $this->getUserId());
            if ($result) {
                return response()->redirectTo('user.php?act=booking_list');
            }
        }
    }
}
