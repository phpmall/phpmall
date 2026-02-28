<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));

        $exc = new Exchange(ecs()->table('shop_card'), db(), 'card_id', 'card_name');

        /**
         * 包装列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('07_card_list'));
            $this->assign('action_link', ['text' => lang('card_add'), 'href' => 'card.php?act=add']);
            $this->assign('full_page', 1);

            $cards_list = $this->cards_list();

            $this->assign('card_list', $cards_list['card_list']);
            $this->assign('filter', $cards_list['filter']);
            $this->assign('record_count', $cards_list['record_count']);
            $this->assign('page_count', $cards_list['page_count']);

            return $this->display('card_list');
        }

        /**
         * ajax列表
         */
        if ($action === 'query') {
            $cards_list = $this->cards_list();
            $this->assign('card_list', $cards_list['card_list']);
            $this->assign('filter', $cards_list['filter']);
            $this->assign('record_count', $cards_list['record_count']);
            $this->assign('page_count', $cards_list['page_count']);

            $sort_flag = MainHelper::sort_flag($cards_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('card_list'), '', ['filter' => $cards_list['filter'], 'page_count' => $cards_list['page_count']]);
        }
        /**
         * 删除贺卡
         */
        if ($action === 'remove') {
            $this->check_authz_json('card_manage');

            $card_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

            $name = $exc->get_name($card_id);
            $img = $exc->get_name($card_id, 'card_img');

            if ($exc->drop($card_id)) {
                // 删除图片
                if (! empty($img)) {
                    @unlink('../'.DATA_DIR.'/cardimg/'.$img);
                }
                $this->admin_log(addslashes($name), 'remove', 'card');

                $url = 'card.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error('DB error');
            }
        }
        /**
         * 添加新包装
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('card_manage');

            // 初始化显示
            $card['card_fee'] = 0;
            $card['free_money'] = 0;

            $this->assign('card', $card);
            $this->assign('ur_here', lang('card_add'));
            $this->assign('action_link', ['text' => lang('07_card_list'), 'href' => 'card.php?act=list']);
            $this->assign('form_action', 'insert');

            return $this->display('card_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('card_manage');

            // 检查包装名是否重复
            $is_only = $exc->is_only('card_name', $_POST['card_name']);

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('cardname_exist'), stripslashes($_POST['card_name'])), 1);
            }

            // 处理图片
            $img_name = basename($image->upload_image($_FILES['card_img'], 'cardimg'));

            // 插入数据
            DB::table('shop_card')->insert([
                'card_name' => $_POST['card_name'],
                'card_fee' => $_POST['card_fee'],
                'free_money' => $_POST['free_money'],
                'card_desc' => $_POST['card_desc'],
                'card_img' => $img_name,
            ]);

            $this->admin_log($_POST['card_name'], 'add', 'card');

            // 添加链接
            $link[0]['text'] = lang('continue_add');
            $link[0]['href'] = 'card.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'card.php?act=list';

            return $this->sys_msg($_POST['card_name'].lang('cardadd_succeed'), 0, $link);
        }

        /**
         * 编辑包装
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('card_manage');

            $card = DB::table('shop_card')
                ->select('card_id', 'card_name', 'card_fee', 'free_money', 'card_desc', 'card_img')
                ->where('card_id', $_REQUEST['id'])
                ->first();
            $card = $card ? (array) $card : [];

            $this->assign('ur_here', lang('card_edit'));
            $this->assign('action_link', ['text' => lang('07_card_list'), 'href' => 'card.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('card', $card);
            $this->assign('form_action', 'update');

            return $this->display('card_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('card_manage');

            if ($_POST['card_name'] != $_POST['old_cardname']) {
                // 检查品牌名是否相同
                $is_only = $exc->is_only('card_name', $_POST['card_name'], $_POST['id']);

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('cardname_exist'), stripslashes($_POST['card_name'])), 1);
                }
            }
            $param = "card_name = '$_POST[card_name]', card_fee = '$_POST[card_fee]', free_money= $_POST[free_money], card_desc = '$_POST[card_desc]'";
            // 处理图片
            $img_name = basename($image->upload_image($_FILES['card_img'], 'cardimg', $_POST['old_cardimg']));
            if ($img_name) {
                $param .= "  ,card_img ='$img_name' ";
            }

            if ($exc->edit($param, $_POST['id'])) {
                $this->admin_log($_POST['card_name'], 'edit', 'card');

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'card.php?act=list&'.MainHelper::list_link_postfix();

                $note = sprintf(lang('cardedit_succeed'), $_POST['card_name']);

                return $this->sys_msg($note, 0, $link);
            } else {
                return $this->sys_msg(lang('edit_failed'), 1);
            }
        }

        // 删除卡片图片
        if ($action === 'drop_card_img') {
            // 权限判断
            $this->admin_priv('card_manage');
            $card_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // 取得logo名称
            $img_name = DB::table('shop_card')->where('card_id', $card_id)->value('card_img');

            if (! empty($img_name)) {
                @unlink(ROOT_PATH.DATA_DIR.'/cardimg/'.$img_name);
                DB::table('shop_card')->where('card_id', $card_id)->update(['card_img' => '']);
            }
            $link = [['text' => lang('card_edit_lnk'), 'href' => 'card.php?act=edit&id='.$card_id], ['text' => lang('card_list_lnk'), 'href' => 'brand.php?act=list']];

            return $this->sys_msg(lang('drop_card_img_success'), 0, $link);
        }
        /**
         * ajax编辑卡片名字
         */
        if ($action === 'edit_card_name') {
            $this->check_authz_json('card_manage');
            $card_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $card_name = empty($_REQUEST['val']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['val']));

            if (! $exc->is_only('card_name', $card_name, $card_id)) {
                return $this->make_json_error(sprintf(lang('cardname_exist'), $card_name));
            }
            $old_card_name = $exc->get_name($card_id);
            if ($exc->edit("card_name='$card_name'", $card_id)) {
                $this->admin_log(addslashes($old_card_name), 'edit', 'card');

                return $this->make_json_result(stripcslashes($card_name));
            } else {
                return $this->make_json_error('DB error');
            }
        }
        /**
         * ajax编辑卡片费用
         */
        if ($action === 'edit_card_fee') {
            $this->check_authz_json('card_manage');
            $card_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $card_fee = empty($_REQUEST['val']) ? 0.00 : floatval($_REQUEST['val']);

            $card_name = $exc->get_name($card_id);
            if ($exc->edit("card_fee ='$card_fee'", $card_id)) {
                $this->admin_log(addslashes($card_name), 'edit', 'card');

                return $this->make_json_result($card_fee);
            } else {
                return $this->make_json_error('DB error');
            }
        }
        /**
         * ajax编辑免费额度
         */
        if ($action === 'edit_free_money') {
            $this->check_authz_json('card_manage');
            $card_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $free_money = empty($_REQUEST['val']) ? 0.00 : floatval($_REQUEST['val']);

            $card_name = $exc->get_name($card_id);
            if ($exc->edit("free_money ='$free_money'", $card_id)) {
                $this->admin_log(addslashes($card_name), 'edit', 'card');

                return $this->make_json_result($free_money);
            } else {
                return $this->make_json_error('DB error');
            }
        }
    }

    private function cards_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'card_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            // 分页大小
            $filter['record_count'] = DB::table('shop_card')->count();

            $filter = MainHelper::page_and_size($filter);

            // 查询
            $res = DB::table('shop_card')
                ->select('card_id', 'card_name', 'card_img', 'card_fee', 'free_money', 'card_desc')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            // MainHelper::set_filter($filter, $sql); // Cannot easily set_filter for Query Builder
        } else {
            $res = DB::select($result['sql']);
            $filter = $result['filter'];
        }

        $card_list = [];
        foreach ($res as $row) {
            $card_list[] = (array) $row;
        }

        $arr = ['card_list' => $card_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
