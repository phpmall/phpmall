<?php

declare(strict_types=1);

namespace App\Plugins\Integrate;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Integrate
{
    // 整合对象使用的数据库主机
    public $db_host = '';

    // 整合对象使用的数据库名
    public $db_name = '';

    // 整合对象使用的数据库用户名
    public $db_user = '';

    // 整合对象使用的数据库密码
    public $db_pass = '';

    // 整合对象数据表前缀
    public $prefix = '';

    // 数据库所使用编码
    public $charset = '';

    // 整合对象使用的cookie的domain
    public $cookie_domain = '';

    // 整合对象使用的cookie的path
    public $cookie_path = '/';

    // 整合对象会员表名
    public $user_table = '';

    // 会员ID的字段名
    public $field_id = '';

    // 会员名称的字段名
    public $field_name = '';

    // 会员密码的字段名
    public $field_pass = '';

    // 会员邮箱的字段名
    public $field_email = '';

    // 会员性别
    public $field_gender = '';

    // 会员生日
    public $field_bday = '';

    // 注册日期的字段名
    public $field_reg_date = '';

    // 是否需要同步数据到商城
    public $need_sync = true;

    public $error = 0;

    protected $db;

    /**
     * 会员数据整合插件类的构造函数
     *
     * @param  string  $db_host  数据库主机
     * @param  string  $db_name  数据库名
     * @param  string  $db_user  数据库用户名
     * @param  string  $db_pass  数据库密码
     * @return void
     */
    public function __construct($cfg)
    {
        $this->charset = isset($cfg['db_charset']) ? $cfg['db_charset'] : 'UTF8';
        $this->prefix = isset($cfg['prefix']) ? $cfg['prefix'] : '';
        $this->db_name = isset($cfg['db_name']) ? $cfg['db_name'] : '';
        $this->cookie_domain = isset($cfg['cookie_domain']) ? $cfg['cookie_domain'] : '';
        $this->cookie_path = isset($cfg['cookie_path']) ? $cfg['cookie_path'] : '/';
        $this->need_sync = true;

        $quiet = empty($cfg['quiet']) ? 0 : 1;

        // 初始化数据库
        if (empty($cfg['db_host'])) {
            $this->db_name = ecs()->db_name;
            $this->prefix = ecs()->prefix;
            $this->db = $GLOBALS['db'];
        } else {
            if (empty($cfg['is_latin1'])) {
                $this->db = new cls_mysql($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], $this->charset, null, $quiet);
            } else {
                $this->db = new cls_mysql($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], 'latin1', null, $quiet);
            }
        }

        if (! is_resource($this->db->link_id)) {
            $this->error = 1; // 数据库地址帐号
        } else {
            $this->error = $this->db->errno();
        }
    }

    /**
     *  用户登录函数
     *
     * @param  string  $username
     * @param  string  $password
     * @return void
     */
    public function login($username, $password, $remember = null)
    {
        if ($this->check_user($username, $password) > 0) {
            if ($this->need_sync) {
                $this->sync($username, $password);
            }
            $this->set_session($username);
            $this->set_cookie($username, $remember);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->set_cookie(); // 清除cookie
        $this->set_session(); // 清除session
    }

    /**
     *  添加一个新用户
     *
     *
     * @return int
     */
    public function add_user($username, $password, $email, $gender = -1, $bday = 0, $reg_date = 0, $md5password = '')
    {
        // 将用户添加到整合方
        if ($this->check_user($username) > 0) {
            $this->error = ERR_USERNAME_EXISTS;

            return false;
        }
        // 检查email是否重复
        $sql = 'SELECT '.$this->field_id.
            ' FROM '.$this->table($this->user_table).
            ' WHERE '.$this->field_email." = '$email'";
        if ($this->db->getOne($sql, true) > 0) {
            $this->error = ERR_EMAIL_EXISTS;

            return false;
        }

        $post_username = $username;

        if ($md5password) {
            $post_password = $this->compile_password(['md5password' => $md5password]);
        } else {
            $post_password = $this->compile_password(['password' => $password]);
        }

        $fields = [$this->field_name, $this->field_email, $this->field_pass];
        $values = [$post_username, $email, $post_password];

        if ($gender > -1) {
            $fields[] = $this->field_gender;
            $values[] = $gender;
        }
        if ($bday) {
            $fields[] = $this->field_bday;
            $values[] = $bday;
        }
        if ($reg_date) {
            $fields[] = $this->field_reg_date;
            $values[] = $reg_date;
        }

        $sql = 'INSERT INTO '.$this->table($this->user_table).
            ' ('.implode(',', $fields).')'.
            " VALUES ('".implode("', '", $values)."')";

        $this->db->query($sql);

        if ($this->need_sync) {
            $this->sync($username, $password);
        }

        return true;
    }

    /**
     *  编辑用户信息($password, $email, $gender, $bday)
     *
     *
     * @return void
     */
    public function edit_user($cfg)
    {
        if (empty($cfg['username'])) {
            return false;
        } else {
            $cfg['post_username'] = $cfg['username'];
        }

        $values = [];
        if (! empty($cfg['password']) && empty($cfg['md5password'])) {
            $cfg['md5password'] = md5($cfg['password']);
        }
        if ((! empty($cfg['md5password'])) && $this->field_pass != 'NULL') {
            $values[] = $this->field_pass."='".$this->compile_password(['md5password' => $cfg['md5password']])."'";
        }

        if ((! empty($cfg['email'])) && $this->field_email != 'NULL') {
            // 检查email是否重复
            $sql = 'SELECT '.$this->field_id.
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_email." = '$cfg[email]' ".
                ' AND '.$this->field_name." != '$cfg[post_username]'";
            if ($this->db->getOne($sql, true) > 0) {
                $this->error = ERR_EMAIL_EXISTS;

                return false;
            }
            // 检查是否为新E-mail
            $sql = 'SELECT count(*)'.
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_email." = '$cfg[email]' ";
            if ($this->db->getOne($sql, true) === 0) {
                // 新的E-mail
                DB::table('user')->where('user_name', $cfg['post_username'])->update(['is_validated' => 0]);
            }
            $values[] = $this->field_email."='".$cfg['email']."'";
        }

        if (isset($cfg['gender']) && $this->field_gender != 'NULL') {
            $values[] = $this->field_gender."='".$cfg['gender']."'";
        }

        if ((! empty($cfg['bday'])) && $this->field_bday != 'NULL') {
            $values[] = $this->field_bday."='".$cfg['bday']."'";
        }

        if ($values) {
            $sql = 'UPDATE '.$this->table($this->user_table).
                ' SET '.implode(', ', $values).
                ' WHERE '.$this->field_name."='".$cfg['post_username']."' LIMIT 1";

            $this->db->query($sql);

            if ($this->need_sync) {
                if (empty($cfg['md5password'])) {
                    $this->sync($cfg['username']);
                } else {
                    $this->sync($cfg['username'], '', $cfg['md5password']);
                }
            }
        }

        return true;
    }

    /**
     * 删除用户
     *
     *
     * @return void
     */
    public function remove_user($id)
    {
        $post_id = $id;

        if ($this->need_sync || (isset($this->is_phpmall) && $this->is_phpmall)) {
            // 如果需要同步或是phpmall插件执行这部分代码
            if (is_array($post_id)) {
                $col = DB::table('user')->whereIn('user_name', $post_id)->pluck('user_id')->all();
            } else {
                $col = DB::table('user')->where('user_name', $post_id)->limit(1)->pluck('user_id')->all();
            }

            if ($col) {
                DB::table('user')->whereIn('parent_id', $col)->update(['parent_id' => 0]); // 将删除用户的下级的parent_id 改为0
                DB::table('user')->whereIn('user_id', $col)->delete(); // 删除用户
                // 删除用户订单
                $col_order_id = DB::table('order_info')->whereIn('user_id', $col)->pluck('order_id')->all();
                if ($col_order_id) {
                    DB::table('order_info')->whereIn('order_id', $col_order_id)->delete();
                    DB::table('order_goods')->whereIn('order_id', $col_order_id)->delete();
                }

                DB::table('user_booking')->whereIn('user_id', $col)->delete(); // 删除用户预订
                DB::table('user_collect')->whereIn('user_id', $col)->delete(); // 删除会员收藏商品
                DB::table('feedback')->whereIn('user_id', $col)->delete(); // 删除用户留言
                DB::table('user_address')->whereIn('user_id', $col)->delete(); // 删除用户地址
                DB::table('user_bonus')->whereIn('user_id', $col)->delete(); // 删除用户红包
                DB::table('user_account')->whereIn('user_id', $col)->delete(); // 删除用户帐号金额
                DB::table('user_tag')->whereIn('user_id', $col)->delete(); // 删除用户标记
                DB::table('user_account_log')->whereIn('user_id', $col)->delete(); // 删除用户日志
            }
        }

        if (isset($this->phpmall) && $this->phpmall) {
            // 如果是phpmall插件直接退出
            return;
        }

        $sql = 'DELETE FROM '.$this->table($this->user_table).' WHERE ';
        if (is_array($post_id)) {
            $sql .= db_create_in($post_id, $this->field_name);
        } else {
            $sql .= $this->field_name."='".$post_id."' LIMIT 1";
        }

        $this->db->query($sql);
    }

    /**
     *  获取指定用户的信息
     *
     *
     * @return void
     */
    public function get_profile_by_name($username)
    {
        $post_username = $username;

        $sql = 'SELECT '.$this->field_id.' AS user_id,'.$this->field_name.' AS user_name,'.
            $this->field_email.' AS email,'.$this->field_gender.' AS sex,'.
            $this->field_bday.' AS birthday,'.$this->field_reg_date.' AS reg_time, '.
            $this->field_pass.' AS password '.
            ' FROM '.$this->table($this->user_table).
            ' WHERE '.$this->field_name."='$post_username'";
        $row = $this->db->getRow($sql);

        return $row;
    }

    /**
     *  获取指定用户的信息
     *
     *
     * @return void
     */
    public function get_profile_by_id($id)
    {
        $sql = 'SELECT '.$this->field_id.' AS user_id,'.$this->field_name.' AS user_name,'.
            $this->field_email.' AS email,'.$this->field_gender.' AS sex,'.
            $this->field_bday.' AS birthday,'.$this->field_reg_date.' AS reg_time, '.
            $this->field_pass.' AS password '.
            ' FROM '.$this->table($this->user_table).
            ' WHERE '.$this->field_id."='$id'";
        $row = $this->db->getRow($sql);

        return $row;
    }

    /**
     *  根据登录状态设置cookie
     *
     *
     * @return void
     */
    public function get_cookie()
    {
        $id = $this->check_cookie();
        if ($id) {
            if ($this->need_sync) {
                $this->sync($id);
            }
            $this->set_session($id);

            return true;
        } else {
            return false;
        }
    }

    /**
     *  检查指定用户是否存在及密码是否正确
     *
     * @param  string  $username  用户名
     * @return int
     */
    public function check_user($username, $password = null)
    {
        $post_username = $username;

        // 如果没有定义密码则只检查用户名
        if ($password === null) {
            $sql = 'SELECT '.$this->field_id.
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_name."='".$post_username."'";

            return $this->db->getOne($sql);
        } else {
            $sql = 'SELECT '.$this->field_id.
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_name."='".$post_username."' AND ".$this->field_pass." ='".$this->compile_password(['password' => $password])."'";

            return $this->db->getOne($sql);
        }
    }

    /**
     *  检查指定邮箱是否存在
     *
     * @param  string  $email  用户邮箱
     * @return bool
     */
    public function check_email($email)
    {
        if (! empty($email)) {
            // 检查email是否重复
            $sql = 'SELECT '.$this->field_id.
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_email." = '$email' ";
            if ($this->db->getOne($sql, true) > 0) {
                $this->error = ERR_EMAIL_EXISTS;

                return true;
            }

            return false;
        }
    }

    /**
     *  检查cookie是正确，返回用户名
     *
     *
     * @return void
     */
    public function check_cookie()
    {
        return '';
    }

    /**
     *  设置cookie
     *
     *
     * @return void
     */
    public function set_cookie($username = '', $remember = null)
    {
        if (empty($username)) {
            // 摧毁cookie
            $time = time() - 3600;
            setcookie('ECS[user_id]', '', $time, $this->cookie_path, '', false, true);
            setcookie('ECS[password]', '', $time, $this->cookie_path, '', false, true);
        } elseif ($remember) {
            // 设置cookie
            $time = time() + 3600 * 24 * 15;

            setcookie('ECS[username]', $username, $time, $this->cookie_path, $this->cookie_domain, null, true);
            $row = (array) DB::table('user')->where('user_name', $username)->select('user_id', 'password')->first();
            if ($row) {
                setcookie('ECS[user_id]', $row['user_id'], $time, $this->cookie_path, $this->cookie_domain, null, true);
                setcookie('ECS[password]', $row['password'], $time, $this->cookie_path, $this->cookie_domain, null, true);
            }
        }
    }

    /**
     *  设置指定用户SESSION
     *
     *
     * @return void
     */
    public function set_session($username = '')
    {
        if (empty($username)) {
            $GLOBALS['sess']->destroy_session();
        } else {
            $row = (array) DB::table('user')->where('user_name', $username)->select('user_id', 'password', 'email')->first();

            if ($row) {
                Session::put('user_id', $row['user_id']);
                Session::put('user_name', $username);
                Session::put('email', $row['email']);
            }
        }
    }

    /**
     * 在给定的表名前加上数据库名以及前缀
     *
     * @param  string  $str  表名
     * @return void
     */
    public function table($str)
    {
        return '`'.$this->db_name.'`.`'.$this->prefix.$str.'`';
    }

    /**
     *  编译密码函数
     *
     * @param  array  $cfg  包含参数为 $password, $md5password, $salt, $type
     * @return void
     */
    public function compile_password($cfg)
    {
        // 使用 bcrypt 加密新密码
        if (isset($cfg['password'])) {
            return Hash::make($cfg['password']);
        }

        // 兼容旧逻辑
        if (isset($cfg['md5password'])) {
            if (empty($cfg['type'])) {
                $cfg['type'] = PWD_MD5;
            }

            switch ($cfg['type']) {
                case PWD_MD5:
                    if (! empty($cfg['ec_salt'])) {
                        return md5($cfg['md5password'].$cfg['ec_salt']);
                    } else {
                        return $cfg['md5password'];
                    }

                case PWD_PRE_SALT:
                    if (empty($cfg['salt'])) {
                        $cfg['salt'] = '';
                    }

                    return md5($cfg['salt'].$cfg['md5password']);

                case PWD_SUF_SALT:
                    if (empty($cfg['salt'])) {
                        $cfg['salt'] = '';
                    }

                    return md5($cfg['md5password'].$cfg['salt']);

                default:
                    return '';
            }
        }

        return '';
    }

    /**
     * 验证密码
     *
     * @param  string  $password  明文密码
     * @param  string  $hash  存储的哈希密码
     * @param  string|null  $salt  盐值（旧密码兼容）
     * @return bool
     */
    public function verify_password($password, $hash, $salt = null): bool
    {
        // bcrypt 密码验证
        if (strlen($hash) !== 32) {
            return Hash::check($password, $hash);
        }

        // 旧 MD5 密码兼容验证
        if (!empty($salt)) {
            return $hash === md5(md5($password).$salt);
        }

        return $hash === md5($password);
    }

    /**
     *  会员同步
     *
     *
     * @return void
     */
    public function sync($username, $password = '', $md5password = '')
    {
        if ((! empty($password)) && empty($md5password)) {
            $md5password = md5($password);
        }

        $main_profile = $this->get_profile_by_name($username);

        if (empty($main_profile)) {
            return false;
        }

        // Query the local user table

        $profile = (array) DB::table('user')->where('user_name', $username)->select('user_name', 'email', 'password', 'sex', 'birthday')->first();
        if (empty($profile)) {
            // 向商城表插入一条新记录
            $userData = [
                'user_name' => $username,
                'email' => $main_profile['email'],
                'sex' => $main_profile['sex'],
                'birthday' => $main_profile['birthday'],
                'reg_time' => $main_profile['reg_time'],
            ];

            if (! empty($md5password)) {
                $userData['password'] = $md5password;
            }

            DB::table('user')->insert($userData);

            return true;
        } else {
            $updateData = [];
            if ($main_profile['email'] != $profile['email']) {
                $updateData['email'] = $main_profile['email'];
            }
            if ($main_profile['sex'] != $profile['sex']) {
                $updateData['sex'] = $main_profile['sex'];
            }
            if ($main_profile['birthday'] != $profile['birthday']) {
                $updateData['birthday'] = $main_profile['birthday'];
            }
            if ((! empty($md5password)) && ($md5password != $profile['password'])) {
                $updateData['password'] = $md5password;
            }

            if (empty($updateData)) {
                return true;
            } else {
                DB::table('user')->where('user_name', $username)->update($updateData);

                return true;
            }
        }
    }

    /**
     *  获取论坛有效积分及单位
     *
     *
     * @return void
     */
    public function get_points_name()
    {
        return [];
    }

    /**
     *  获取用户积分
     *
     *
     * @return void
     */
    public function get_points($username)
    {
        $credits = $this->get_points_name();
        $fileds = array_keys($credits);
        if ($fileds) {
            $sql = 'SELECT '.$this->field_id.', '.implode(', ', $fileds).
                ' FROM '.$this->table($this->user_table).
                ' WHERE '.$this->field_name."='$username'";
            $row = $this->db->getRow($sql);

            return $row;
        } else {
            return false;
        }
    }

    /**
     *设置用户积分
     *
     *
     * @return void
     */
    public function set_points($username, $credits)
    {
        $user_set = array_keys($credits);
        $points_set = array_keys($this->get_points_name());

        $set = array_intersect($user_set, $points_set);

        if ($set) {
            $tmp = [];
            foreach ($set as $credit) {
                $tmp[] = $credit.'='.$credit.'+'.$credits[$credit];
            }
            $sql = 'UPDATE '.$this->table($this->user_table).
                ' SET '.implode(', ', $tmp).
                ' WHERE '.$this->field_name." = '$username'";
            $this->db->query($sql);
        }

        return true;
    }

    public function get_user_info($username)
    {
        return $this->get_profile_by_name($username);
    }

    /**
     * 检查有无重名用户，有则返回重名用户
     *
     *
     * @return void
     */
    public function test_conflict($user_list)
    {
        if (empty($user_list)) {
            return [];
        }

        $sql = 'SELECT '.$this->field_name.' FROM '.$this->table($this->user_table).' WHERE '.db_create_in($user_list, $this->field_name);
        $user_list = $this->db->getCol($sql);

        return $user_list;
    }
}
