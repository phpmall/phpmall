<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\CommonHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $action = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'display_my_info';
        if (isset($_POST['sms_sign_update'])) {
            $action = 'sms_sign_update';
        } elseif (isset($_POST['sms_sign_default'])) {
            $action = 'sms_sign_default';
        }

        $sms = new sms;

        if ($action === 'display_send_ui') {
            $this->admin_priv('sms_send');

            if ($sms->has_registered()) {
                $this->assign('ur_here', lang('03_sms_send'));
                $special_ranks = MainHelper::get_rank_list();
                $send_rank['1_0'] = lang('user_list');
                foreach ($special_ranks as $rank_key => $rank_value) {
                    $send_rank['2_'.$rank_key] = $rank_value;
                }

                $this->assign('send_rank', $send_rank);

                return $this->display('sms_send_ui');
            } else {
                $this->assign('ur_here', lang('register_sms'));
                $this->assign('sms_site_info', $sms->get_site_info());

                return $this->display('sms_register_ui');
            }
        }
        if ($action === 'sms_sign') {
            $this->admin_priv('sms_send');

            if ($sms->has_registered()) {
                $row = (array) DB::table('shop_config')->where('code', 'sms_sign')->first();
                if (! empty($row['id'])) {
                    $sms_sign = unserialize($row['value']);
                    $t = [];
                    if (is_array($sms_sign) && isset($sms_sign[cfg('ent_id')])) {
                        foreach ($sms_sign[cfg('ent_id')] as $key => $val) {
                            $t[cfg('ent_id')][$key]['key'] = $key;
                            $t[cfg('ent_id')][$key]['value'] = $val;
                        }
                        $this->assign('sms_sign', $t[cfg('ent_id')]);
                    }
                } else {
                    $this->shop_config_update('sms_sign', '');
                    $this->shop_config_update('default_sms_sign', '');
                }
                $default_sms_sign = (array) DB::table('shop_config')->where('code', 'default_sms_sign')->first();
                $this->assign('default_sign', $default_sms_sign['value']);

                return $this->display('sms_sign');
            } else {
                $this->assign('ur_here', lang('register_sms'));
                $this->assign('sms_site_info', $sms->get_site_info());

                return $this->display('sms_register_ui');
            }
        }
        if ($action === 'sms_sign_add') {
            $this->admin_priv('sms_send');

            if ($sms->has_registered()) {
                if (empty($_POST['sms_sign'])) {
                    return $this->sys_msg(lang('insert_sign'), 1, [], false);
                }

                $row = (array) DB::table('shop_config')->where('code', 'sms_sign')->first();

                if (! empty($row['id'])) {
                    $sms_sign = unserialize($row['value']);
                    $this->assign('sms_sign', $sms_sign);
                    $data = [];
                    $data['shopexid'] = cfg('ent_id');
                    $data['passwd'] = cfg('ent_ac');

                    $content_t = $content_y = trim($_POST['sms_sign']);
                    if (EC_CHARSET != 'utf-8') {
                        $content_t = iconv('gb2312', 'utf-8', $content_y);
                    }

                    $url = 'https://openapi.cn';
                    $key = 'qufoxtpr';
                    $secret = 't66moqjixb2nntiy2io2';
                    $c = new prism_client($url, $key, $secret);
                    $params = [
                        'shopexid' => cfg('ent_id'),
                        'passwd' => cfg('ent_ac'),
                        'content' => $content_t,
                        'content-type' => 'application/x-www-form-urlencoded',
                    ];
                    $result = $c->post('api/addcontent/new', $params);
                    $result = json_decode($result, true);
                    if ($result['res'] === 'succ' && ! empty($result['data']['extend_no'])) {
                        $extend_no = $result['data']['extend_no'];
                        $sms_sign[cfg('ent_id')][$extend_no] = $content_y;
                        $sms_sign = serialize($sms_sign);
                        if (empty(cfg('default_sms_sign'))) {
                            $this->shop_config_update('default_sms_sign', $content_y);
                        }
                        $this->shop_config_update('sms_sign', $sms_sign);
                        // 清除缓存
                        CommonHelper::clear_all_files();

                        return $this->sys_msg(lang('insert_succ'), 1, [], false);
                    } else {
                        $error_smg = $result['data'];
                        if (EC_CHARSET != 'utf-8') {
                            $error_smg = iconv('utf-8', 'gb2312', $error_smg);
                        }

                        return $this->sys_msg($error_smg, 1, [], false);
                    }
                } else {
                    $this->shop_config_update('default_sms_sign', $content_y);
                    $this->shop_config_update('sms_sign', '');
                    // 清除缓存
                    CommonHelper::clear_all_files();

                    return $this->sys_msg(lang('error_smg'), 1, [], false);
                }
            } else {
                $this->assign('ur_here', lang('register_sms'));
                $this->assign('sms_site_info', $sms->get_site_info());

                return $this->display('sms_register_ui');
            }
        }
        if ($action === 'sms_sign_update') {
            $this->admin_priv('sms_send');
            if ($sms->has_registered()) {
                $row = (array) DB::table('shop_config')->where('code', 'sms_sign')->first();
                if (! empty($row['id'])) {
                    $sms_sign = unserialize($row['value']);
                    $this->assign('sms_sign', $sms_sign);
                    $extend_no = $_POST['extend_no'];

                    $content_t = $content_y = $sms_sign[cfg('ent_id')][$extend_no];
                    $new_content_t = $new_content_y = $_POST['new_sms_sign'];

                    if (! isset($sms_sign[cfg('ent_id')][$extend_no]) || empty($extend_no)) {
                        return $this->sys_msg(lang('error_smg'), 1, [], false);
                    }
                    if (EC_CHARSET != 'utf-8') {
                        $content_t = iconv('gb2312', 'utf-8', $content_y);
                        $new_content_t = iconv('gb2312', 'utf-8', $new_content_y);
                    }
                    $url = 'https://openapi.cn';
                    $key = 'qufoxtpr';
                    $secret = 't66moqjixb2nntiy2io2';
                    $c = new prism_client($url, $key, $secret);
                    $params = [
                        'shopexid' => cfg('ent_id'),
                        'passwd' => cfg('ent_ac'),
                        'old_content' => $content_t,
                        'new_content' => $new_content_t,
                        'content-type' => 'application/x-www-form-urlencoded',
                    ];
                    $result = $c->post('api/addcontent/update', $params);
                    $result = json_decode($result, true);

                    if ($result['res'] === 'succ' && ! empty($result['data']['new_extend_no'])) {
                        $new_extend_no = $result['data']['new_extend_no'];
                        unset($sms_sign[cfg('ent_id')][$extend_no]);
                        $sms_sign[cfg('ent_id')][$new_extend_no] = $new_content_y;

                        $sms_sign = serialize($sms_sign);
                        if (empty(cfg('default_sms_sign'))) {
                            $this->shop_config_update('default_sms_sign', $new_content_y);
                        }
                        $this->shop_config_update('sms_sign', $sms_sign);

                        // 清除缓存
                        CommonHelper::clear_all_files();

                        return $this->sys_msg(lang('edit_succ'), 1, [], false);
                    } else {
                        $error_smg = $result['data'];
                        if (EC_CHARSET != 'utf-8') {
                            $error_smg = iconv('utf-8', 'gb2312', $error_smg);
                        }

                        return $this->sys_msg($error_smg, 1, [], false);
                    }
                } else {
                    $this->shop_config_update('default_sms_sign', $content_y);
                    $this->shop_config_update('sms_sign', '');
                    // 清除缓存
                    CommonHelper::clear_all_files();

                    return $this->sys_msg(lang('error_smg'), 1, [], false);
                }
            } else {
                $this->assign('ur_here', lang('register_sms'));
                $this->assign('sms_site_info', $sms->get_site_info());

                return $this->display('sms_register_ui');
            }
        }
        if ($action === 'sms_sign_default') {
            $this->admin_priv('sms_send');
            if ($sms->has_registered()) {
                $row = (array) DB::table('shop_config')->where('code', 'sms_sign')->first();
                if (! empty($row['id'])) {
                    $sms_sign = unserialize($row['value']);
                    $this->assign('sms_sign', $sms_sign);
                    $data = [];
                    $data['shopexid'] = cfg('ent_id');
                    $data['passwd'] = cfg('ent_ac');

                    $extend_no = $_POST['extend_no'];

                    $sms_sign_default = $sms_sign[cfg('ent_id')][$extend_no];
                    if (! empty($sms_sign_default)) {
                        $this->shop_config_update('default_sms_sign', $sms_sign_default);
                        // 清除缓存
                        CommonHelper::clear_all_files();

                        return $this->sys_msg(lang('default_succ'), 1, [], false);
                    } else {
                        return $this->sys_msg(lang('no_default'), 1, [], false);
                    }
                } else {
                    $this->shop_config_update('default_sms_sign', $content_y);
                    $this->shop_config_update('sms_sign', '');
                    // 清除缓存
                    CommonHelper::clear_all_files();

                    return $this->sys_msg(lang('error_smg'), 1, [], false);
                }
            } else {
                $this->assign('ur_here', lang('register_sms'));
                $this->assign('sms_site_info', $sms->get_site_info());

                return $this->display('sms_register_ui');
            }

            // 发送短信
        }
        if ($action === 'send_sms') {
            $send_num = isset($_POST['send_num']) ? $_POST['send_num'] : '';
            // 除了后台手动发的为营销短信  其它暂时默认都为通知短信
            $sms_type = isset($_POST['sms_type']) ? $_POST['sms_type'] : '';

            if (isset($send_num)) {
                $phone = $send_num.',';
            }

            $send_rank = isset($_POST['send_rank']) ? $_POST['send_rank'] : 0;

            if ($send_rank != 0) {
                $rank_array = explode('_', $send_rank);

                if ($rank_array['0'] === 1) {
                    $row = DB::table('user')
                        ->where('mobile_phone', '<>', '')
                        ->select('mobile_phone')
                        ->get();
                    foreach ($row as $rank_rs) {
                        $rank_rs = (array) $rank_rs;
                        $value[] = $rank_rs['mobile_phone'];
                    }
                } else {
                    $rank_row = (array) DB::table('user_rank')->where('rank_id', (int) $rank_array['1'])->first();

                    if ($rank_row['special_rank'] === 1) {
                        $row = DB::table('user')
                            ->where('mobile_phone', '<>', '')
                            ->where('user_rank', (int) $rank_array['1'])
                            ->select('mobile_phone')
                            ->get();
                    } else {
                        $row = DB::table('user')
                            ->where('mobile_phone', '<>', '')
                            ->where('rank_points', '>', $rank_row['min_points'])
                            ->where('rank_points', '<', $rank_row['max_points'])
                            ->select('mobile_phone')
                            ->get();
                    }

                    foreach ($row as $rank_rs) {
                        $rank_rs = (array) $rank_rs;
                        $value[] = $rank_rs['mobile_phone'];
                    }
                }
                if (isset($value)) {
                    $phone .= implode(',', $value);
                }
            }

            $msg = isset($_POST['msg']) ? $_POST['msg'] : '';

            $send_date = isset($_POST['send_date']) ? $_POST['send_date'] : '';

            $result = $sms->send($phone, $msg, $send_date, $send_num = 13, $sms_type);

            $link[] = [
                'text' => lang('back').lang('03_sms_send'),
                'href' => 'sms.php?act=display_send_ui',
            ];

            if ($result === true) {// 发送成功
                return $this->sys_msg(lang('send_ok'), 0, $link);
            } else {
                @$error_detail = lang('server_errors')[$sms->errors['server_errors']['error_no']]
                    .lang('api_errors.send')[$sms->errors['api_errors']['error_no']];

                return $this->sys_msg(lang('send_error').$error_detail, 1, $link);
            }
        }
    }

    private function shop_config_update($config_code, $config_value)
    {
        $c_node_id = DB::table('shop_config')->where('code', $config_code)->value('id');
        if (empty($c_node_id)) {
            for ($i = 247; $i <= 270; $i++) {
                $c_id = DB::table('shop_config')->where('id', $i)->value('id');
                if (empty($c_id)) {
                    DB::table('shop_config')->insert([
                        'id' => $i,
                        'parent_id' => 2,
                        'code' => $config_code,
                        'type' => 'hidden',
                        'value' => $config_value,
                        'sort_order' => 1,
                    ]);
                    break;
                }
            }
        } else {
            DB::table('shop_config')->where('code', $config_code)->update(['value' => $config_value]);
        }
    }
}
