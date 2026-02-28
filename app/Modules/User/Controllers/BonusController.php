<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\MainHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 我的红包列表
        if ($action === 'bonus') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
            $record_count = DB::table('user_bonus')->where('user_id', $this->getUserId())->count();

            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);
            $bonus = TransactionHelper::get_user_bouns_list($this->getUserId(), $pager['size'], $pager['start']);

            $this->assign('pager', $pager);
            $this->assign('bonus', $bonus);

            return $this->display('user_transaction');
        }

        // 添加一个红包
        if ($action === 'act_add_bonus') {
            $bouns_sn = isset($_POST['bonus_sn']) ? intval($_POST['bonus_sn']) : '';

            if (TransactionHelper::add_bonus($this->getUserId(), $bouns_sn)) {
                $this->show_message(lang('add_bonus_sucess'), lang('back_up_page'), 'user.php?act=bonus', 'info');
            } else {
                $this->show_message(lang('back_up_page'), lang('back_up_page'), 'user.php?act=bonus', 'error');
            }
        }
    }
}
