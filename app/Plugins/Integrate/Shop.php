<?php

declare(strict_types=1);

namespace App\Plugins\Integrate;

use Illuminate\Support\Facades\Hash;

// 模块的基本信息
if (isset($set_modules) && $set_modules === true) {
    $i = (isset($modules)) ? count($modules) : 0;

    // 会员数据整合插件的代码必须和文件名保持一致
    $modules[$i]['code'] = 'phpmall';

    // 被整合的第三方程序的名称
    $modules[$i]['name'] = 'PHPMall';

    // 被整合的第三方程序的版本
    $modules[$i]['version'] = '2.0';

    // 插件的作者
    $modules[$i]['author'] = 'PHPMall R&D TEAM';

    // 插件作者的官方网站
    $modules[$i]['website'] = 'http://www.phpmall.net';

    return;
}

class Shop extends Integrate
{
    public $is_phpmall = 1;

    public function __construct($cfg)
    {
        parent::__construct([]);
        $this->user_table = 'users';
        $this->field_id = 'user_id';
        $this->ec_salt = 'ec_salt';
        $this->field_name = 'user_name';
        $this->field_pass = 'password';
        $this->field_email = 'email';
        $this->field_gender = 'sex';
        $this->field_bday = 'birthday';
        $this->field_reg_date = 'reg_time';
        $this->need_sync = false;
        $this->is_phpmall = 1;
    }

    /**
     *  检查指定用户是否存在及密码是否正确(重载基类check_user函数，支持zc加密方法)
     *
     * @param  string  $username  用户名
     * @return int
     */
    public function check_user($username, $password = null)
    {
        if ($this->charset != 'UTF8') {
            $post_username = ecs_iconv('UTF8', $this->charset, $username);
        } else {
            $post_username = $username;
        }

        if ($password === null) {
            $sql = 'SELECT '.$this->field_id.
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_name."='".$post_username."'";

            return $this->db->getOne($sql);
        } else {
            $sql = 'SELECT user_id, password, salt,ec_salt '.
                ' FROM '.$this->table($this->user_table).
                " WHERE user_name='$post_username'";
            $row = $this->db->getRow($sql);
            $ec_salt = $row['ec_salt'];
            if (empty($row)) {
                return 0;
            }

            // 使用新的密码验证方法（兼容旧 MD5 密码）
            if ($this->verify_password($password, $row['password'], $row['salt'] ?? null)) {
                // 验证成功，如果是旧 MD5 密码则升级到 bcrypt
                if (strlen($row['password']) === 32) {
                    $new_password = Hash::make($password);
                    $sql = 'UPDATE '.$this->table($this->user_table)."SET password= '".$new_password."',ec_salt= NULL, salt= NULL ".
                        " WHERE user_name='$post_username'";
                    $this->db->query($sql);
                }

                return $row['user_id'];
            }

            // 保留旧逻辑作为备用（用于兼容其他加密方式）
            if (empty($row['salt'])) {
                if ($row['password'] != $this->compile_password(['password' => $password, 'ec_salt' => $ec_salt])) {
                    return 0;
                } else {
                    // 升级到 bcrypt
                    $new_password = Hash::make($password);
                    $sql = 'UPDATE '.$this->table($this->user_table)."SET password= '".$new_password."',ec_salt= NULL ".
                        " WHERE user_name='$post_username'";
                    $this->db->query($sql);

                    return $row['user_id'];
                }
            } else {
                // 如果salt存在，使用salt方式加密验证
                $encrypt_type = substr($row['salt'], 0, 1);
                $encrypt_salt = substr($row['salt'], 1);

                // 计算加密后密码
                $encrypt_password = '';
                switch ($encrypt_type) {
                    case ENCRYPT_ZC:
                        $encrypt_password = md5($encrypt_salt.$password);
                        break;
                    case ENCRYPT_UC:
                        $encrypt_password = md5(md5($password).$encrypt_salt);
                        break;

                    default:
                        $encrypt_password = '';

                }

                if ($row['password'] != $encrypt_password) {
                    return 0;
                }

                $sql = 'UPDATE '.$this->table($this->user_table).
                    " SET password = '".$this->compile_password(['password' => $password])."', salt=''".
                    " WHERE user_id = '$row[user_id]'";
                $this->db->query($sql);

                return $row['user_id'];
            }
        }
    }
}
