<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends BaseController
{
    // 个人资料页面
    public function index(Request $request)
    {
        $action = $request->get('act');
        $user_info = TransactionHelper::get_profile($this->getUserId());

        // 取出注册扩展字段
        $extend_info_list = DB::table('user_extend_fields')
            ->where('type', '<', 2)
            ->where('display', 1)
            ->orderBy('dis_order')
            ->orderBy('id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $extend_info_arr = DB::table('user_extend_info')
            ->select('reg_field_id', 'content')
            ->where('user_id', $this->getUserId())
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $temp_arr = [];
        foreach ($extend_info_arr as $val) {
            $temp_arr[$val['reg_field_id']] = $val['content'];
        }

        foreach ($extend_info_list as $key => $val) {
            switch ($val['id']) {
                case 1:
                    $extend_info_list[$key]['content'] = $user_info['msn'];
                    break;
                case 2:
                    $extend_info_list[$key]['content'] = $user_info['qq'];
                    break;
                case 3:
                    $extend_info_list[$key]['content'] = $user_info['office_phone'];
                    break;
                case 4:
                    $extend_info_list[$key]['content'] = $user_info['home_phone'];
                    break;
                case 5:
                    $extend_info_list[$key]['content'] = $user_info['mobile_phone'];
                    break;
                default:
                    $extend_info_list[$key]['content'] = empty($temp_arr[$val['id']]) ? '' : $temp_arr[$val['id']];
            }
        }

        $this->assign('extend_info_list', $extend_info_list);

        // 密码提示问题
        $this->assign('passwd_questions', lang('passwd_questions'));

        $this->assign('profile', $user_info);

        return $this->display('user_transaction');
    }

    // 修改个人资料的处理
    public function update()
    {
        $birthday = trim($_POST['birthdayYear']).'-'.trim($_POST['birthdayMonth']).'-'.
            trim($_POST['birthdayDay']);
        $email = trim($_POST['email']);
        $other['msn'] = $msn = isset($_POST['extend_field1']) ? trim($_POST['extend_field1']) : '';
        $other['qq'] = $qq = isset($_POST['extend_field2']) ? trim($_POST['extend_field2']) : '';
        $other['office_phone'] = $office_phone = isset($_POST['extend_field3']) ? trim($_POST['extend_field3']) : '';
        $other['home_phone'] = $home_phone = isset($_POST['extend_field4']) ? trim($_POST['extend_field4']) : '';
        $other['mobile_phone'] = $mobile_phone = isset($_POST['extend_field5']) ? trim($_POST['extend_field5']) : '';
        $sel_question = empty($_POST['sel_question']) ? '' : BaseHelper::compile_str($_POST['sel_question']);
        $passwd_answer = isset($_POST['passwd_answer']) ? BaseHelper::compile_str(trim($_POST['passwd_answer'])) : '';

        // 更新用户扩展字段的数据
        $fields_arr = DB::table('user_extend_fields')
            ->where('type', 0)
            ->where('display', 1)
            ->orderBy('dis_order')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        foreach ($fields_arr as $val_id) {       // 循环更新扩展用户信息
            $extend_field_index = 'extend_field'.$val_id;
            if (isset($_POST[$extend_field_index])) {
                $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr(htmlspecialchars($_POST[$extend_field_index]), 0, 99) : htmlspecialchars($_POST[$extend_field_index]);

                DB::table('user_extend_info')->updateOrInsert(
                    ['reg_field_id' => $val_id, 'user_id' => $this->getUserId()],
                    ['content' => $temp_field_content]
                );
            }
        }

        // 写入密码提示问题和答案
        if (! empty($passwd_answer) && ! empty($sel_question)) {
            DB::table('users')
                ->where('user_id', $this->getUserId())
                ->update([
                    'passwd_question' => $sel_question,
                    'passwd_answer' => $passwd_answer,
                ]);
        }

        if (! empty($office_phone) && ! preg_match('/^[\d|\_|\-|\s]+$/', $office_phone)) {
            $this->show_message(lang('passport_js.office_phone_invalid'));
        }
        if (! empty($home_phone) && ! preg_match('/^[\d|\_|\-|\s]+$/', $home_phone)) {
            $this->show_message(lang('passport_js.home_phone_invalid'));
        }
        if (! CommonHelper::is_email($email)) {
            $this->show_message(lang('msg_email_format'));
        }
        if (! empty($msn) && ! CommonHelper::is_email($msn)) {
            $this->show_message(lang('passport_js.msn_invalid'));
        }
        if (! empty($qq) && ! preg_match('/^\d+$/', $qq)) {
            $this->show_message(lang('passport_js.qq_invalid'));
        }
        if (! empty($mobile_phone) && ! preg_match('/^[\d-\s]+$/', $mobile_phone)) {
            $this->show_message(lang('passport_js.mobile_phone'));
        }

        $profile = [
            'user_id' => $this->getUserId(),
            'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
            'sex' => isset($_POST['sex']) ? intval($_POST['sex']) : 0,
            'birthday' => $birthday,
            'other' => isset($other) ? $other : [],
        ];

        if (TransactionHelper::edit_profile($profile)) {
            $this->show_message(lang('edit_profile_success'), lang('profile_lnk'), 'user.php?act=profile', 'info');
        } else {
            if ($user->error === ERR_EMAIL_EXISTS) {
                $msg = sprintf(lang('email_exist'), $profile['email']);
            } else {
                $msg = lang('edit_profile_failed');
            }
            $this->show_message($msg, '', '', 'info');
        }
    }
}
