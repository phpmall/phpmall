<?php

declare(strict_types=1);

namespace App\Helpers;

class BaseHelper
{
    /**
     * 获得用户的真实IP地址
     *
     * @return string
     */
    public static function real_ip()
    {
        static $realip = null;

        if ($realip !== null) {
            return $realip;
        }

        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                // 取X-Forwarded-For中第一个非unknown的有效IP字符串
                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }

    /**
     * 邮件发送
     *
     * @param: $name[string]        接收人姓名
     *
     * @param: $email[string]       接收人邮件地址
     *
     * @param: $subject[string]     邮件标题
     *
     * @param: $content[string]     邮件内容
     *
     * @param: $type[int]           0 普通邮件， 1 HTML邮件
     *
     * @param: $notification[bool]  true 要求回执， false 不用回执
     *
     * @return bool
     */
    public static function send_mail($name, $email, $subject, $content, $type = 0, $notification = false)
    {
        // 如果邮件编码不是EC_CHARSET，创建字符集转换对象，转换编码
        if (cfg('mail_charset') != EC_CHARSET) {
            $name = BaseHelper::ecs_iconv(EC_CHARSET, cfg('mail_charset'), $name);
            $subject = BaseHelper::ecs_iconv(EC_CHARSET, cfg('mail_charset'), $subject);
            $content = BaseHelper::ecs_iconv(EC_CHARSET, cfg('mail_charset'), $content);
            $shop_name = BaseHelper::ecs_iconv(EC_CHARSET, cfg('mail_charset'), cfg('shop_name'));
        }
        $charset = cfg('mail_charset');
        /**
         * 使用mail函数发送邮件
         */
        if (cfg('mail_service') === 0 && function_exists('mail')) {
            // 邮件的头部信息
            $content_type = ($type === 0) ? 'Content-Type: text/plain; charset='.$charset : 'Content-Type: text/html; charset='.$charset;
            $headers = [];
            $headers[] = 'From: "'.'=?'.$charset.'?B?'.base64_encode($shop_name).'?='.'" <'.cfg('smtp_mail').'>';
            $headers[] = $content_type.'; format=flowed';
            if ($notification) {
                $headers[] = 'Disposition-Notification-To: '.'=?'.$charset.'?B?'.base64_encode($shop_name).'?='.'" <'.cfg('smtp_mail').'>';
            }

            $res = @mail($email, '=?'.$charset.'?B?'.base64_encode($subject).'?=', $content, implode("\r\n", $headers));

            if (! $res) {
                err()->add(lang('sendemail_false'));

                return false;
            } else {
                return true;
            }
        } /**
     * 使用smtp服务发送邮件
     */
        else {
            // 邮件的头部信息
            $content_type = ($type === 0) ?
                'Content-Type: text/plain; charset='.$charset : 'Content-Type: text/html; charset='.$charset;
            $content = base64_encode($content);

            $headers = [];
            $headers[] = 'Date: '.gmdate('D, j M Y H:i:s').' +0000';
            $headers[] = 'To: "'.'=?'.$charset.'?B?'.base64_encode($name).'?='.'" <'.$email.'>';
            $headers[] = 'From: "'.'=?'.$charset.'?B?'.base64_encode($shop_name).'?='.'" <'.cfg('smtp_mail').'>';
            $headers[] = 'Subject: '.'=?'.$charset.'?B?'.base64_encode($subject).'?=';
            $headers[] = $content_type.'; format=flowed';
            $headers[] = 'Content-Transfer-Encoding: base64';
            $headers[] = 'Content-Disposition: inline';
            if ($notification) {
                $headers[] = 'Disposition-Notification-To: '.'=?'.$charset.'?B?'.base64_encode($shop_name).'?='.'" <'.cfg('smtp_mail').'>';
            }

            // 获得邮件服务器的参数设置
            $params['host'] = cfg('smtp_host');
            $params['port'] = cfg('smtp_port');
            $params['user'] = cfg('smtp_user');
            $params['pass'] = cfg('smtp_pass');

            if (empty($params['host']) || empty($params['port'])) {
                // 如果没有设置主机和端口直接返回 false
                err()->add(lang('smtp_setting_error'));

                return false;
            } else {
                // 发送邮件
                if (! function_exists('fsockopen')) {
                    // 如果fsockopen被禁用，直接返回
                    err()->add(lang('disabled_fsockopen'));

                    return false;
                }

                static $smtp;

                $send_params['recipients'] = $email;
                $send_params['headers'] = $headers;
                $send_params['from'] = cfg('smtp_mail');
                $send_params['body'] = $content;

                if (! isset($smtp)) {
                    $smtp = new smtp($params);
                }

                if ($smtp->connect() && $smtp->send($send_params)) {
                    return true;
                } else {
                    $err_msg = $smtp->error_msg();
                    if (empty($err_msg)) {
                        err()->add('Unknown Error');
                    } else {
                        if (strpos($err_msg, 'Failed to connect to server') !== false) {
                            err()->add(sprintf(lang('smtp_connect_failure'), $params['host'].':'.$params['port']));
                        } elseif (strpos($err_msg, 'AUTH command failed') !== false) {
                            err()->add(lang('smtp_login_failure'));
                        } elseif (strpos($err_msg, 'bad sequence of commands') !== false) {
                            err()->add(lang('smtp_refuse'));
                        } else {
                            err()->add($err_msg);
                        }
                    }

                    return false;
                }
            }
        }
    }

    /**
     * 获得服务器上的 GD 版本
     */
    public static function gd_version(): float
    {
        if (function_exists('gd_info')) {
            $gd_info = gd_info();
            if (isset($gd_info['GD Version'])) {
                preg_match('/\d+/', $gd_info['GD Version'], $matches);

                return $matches[0] ? floatval($matches[0]) : 0;
            }

            return 0;
        } else {
            return 0;
        }
    }

    /**
     * 检查目标文件夹是否存在，如果不存在则自动创建该目录
     *
     * @param string      folder     目录路径。不能使用相对于网站根目录的URL
     * @return bool
     */
    public static function make_dir($folder)
    {
        $reval = false;

        if (! file_exists($folder)) {
            // 如果目录不存在则尝试创建该目录
            @umask(0);

            // 将目录路径拆分成数组
            preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);

            // 如果第一个字符为/则当作物理路径处理
            $base = ($atmp[0][0] === '/') ? '/' : '';

            // 遍历包含路径信息的数组
            foreach ($atmp[1] as $val) {
                if ($val != '') {
                    $base .= $val;

                    if ($val === '..' || $val === '.') {
                        // 如果目录为.或者..则直接补/继续下一个循环
                        $base .= '/';

                        continue;
                    }
                } else {
                    continue;
                }

                $base .= '/';

                if (! file_exists($base)) {
                    // 尝试创建目录，如果创建失败则继续循环
                    if (@mkdir(rtrim($base, '/'), 0777)) {
                        @chmod($base, 0777);
                        $reval = true;
                    }
                }
            }
        } else {
            // 路径已经存在。返回该路径是不是一个目录
            $reval = is_dir($folder);
        }

        clearstatcache();

        return $reval;
    }

    /**
     * 获得系统是否启用了 gzip
     *
     *
     * @return bool
     */
    public static function gzip_enabled()
    {
        static $enabled_gzip = null;

        if ($enabled_gzip === null) {
            $enabled_gzip = (cfg('enable_gzip') && function_exists('ob_gzhandler'));
        }

        return $enabled_gzip;
    }

    /**
     * 递归方式的对变量中的特殊字符进行转义
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function addslashes_deep($value)
    {
        if (empty($value)) {
            return $value;
        } else {
            return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
        }
    }

    /**
     * 将对象成员变量或者数组的特殊字符进行转义
     *
     * @param  mixed  $obj  对象或者数组
     * @return mixed 对象或者数组
     */
    public static function addslashes_deep_obj($obj)
    {
        if (is_object($obj)) {
            foreach ($obj as $key => $val) {
                $obj->$key = BaseHelper::addslashes_deep($val);
            }
        } else {
            $obj = BaseHelper::addslashes_deep($obj);
        }

        return $obj;
    }

    /**
     * 递归方式的对变量中的特殊字符去除转义
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function stripslashes_deep($value)
    {
        if (empty($value)) {
            return $value;
        } else {
            return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
        }
    }

    /**
     *  将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
     *
     * @param  string  $str  待转换字串
     * @return string $str         处理后字串
     */
    public static function make_semiangle($str)
    {
        $arr = ['０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
            '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' '];

        return strtr($str, $arr);
    }

    /**
     * 过滤用户输入的基本数据，防止script攻击
     *
     * @return string
     */
    public static function compile_str($str)
    {
        $arr = ['<' => '＜', '>' => '＞', '"' => '”', "'" => '’'];

        return strtr($str, $arr);
    }

    /**
     * 检查文件类型
     *
     * @param string      filename            文件名
     * @param string      realname            真实文件名
     * @param string      limit_ext_types     允许的文件类型
     * @return string
     */
    public static function check_file_type($filename, $realname = '', $limit_ext_types = '')
    {
        if ($realname) {
            $extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
        } else {
            $extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
        }

        if ($limit_ext_types && stristr($limit_ext_types, '|'.$extname.'|') === false) {
            return '';
        }

        $str = $format = '';

        $file = @fopen($filename, 'rb');
        if ($file) {
            $str = @fread($file, 0x400); // 读取前 1024 个字节
            @fclose($file);
        } else {
            if (stristr($filename, ROOT_PATH) === false) {
                if ($extname === 'jpg' || $extname === 'jpeg' || $extname === 'gif' || $extname === 'png' || $extname === 'doc' ||
                    $extname === 'xls' || $extname === 'txt' || $extname === 'zip' || $extname === 'rar' || $extname === 'ppt' ||
                    $extname === 'pdf' || $extname === 'rm' || $extname === 'mid' || $extname === 'wav' || $extname === 'bmp' ||
                    $extname === 'swf' || $extname === 'chm' || $extname === 'sql' || $extname === 'cert' || $extname === 'pptx' ||
                    $extname === 'xlsx' || $extname === 'docx') {
                    $format = $extname;
                }
            } else {
                return '';
            }
        }

        if ($format === '' && strlen($str) >= 2) {
            if (substr($str, 0, 4) === 'MThd' && $extname != 'txt') {
                $format = 'mid';
            } elseif (substr($str, 0, 4) === 'RIFF' && $extname === 'wav') {
                $format = 'wav';
            } elseif (substr($str, 0, 3) === "\xFF\xD8\xFF") {
                $format = 'jpg';
            } elseif (substr($str, 0, 4) === 'GIF8' && $extname != 'txt') {
                $format = 'gif';
            } elseif (substr($str, 0, 8) === "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
                $format = 'png';
            } elseif (substr($str, 0, 2) === 'BM' && $extname != 'txt') {
                $format = 'bmp';
            } elseif ((substr($str, 0, 3) === 'CWS' || substr($str, 0, 3) === 'FWS') && $extname != 'txt') {
                $format = 'swf';
            } elseif (substr($str, 0, 4) === "\xD0\xCF\x11\xE0") {   // D0CF11E === DOCFILE === Microsoft Office Document
                if (substr($str, 0x200, 4) === "\xEC\xA5\xC1\x00" || $extname === 'doc') {
                    $format = 'doc';
                } elseif (substr($str, 0x200, 2) === "\x09\x08" || $extname === 'xls') {
                    $format = 'xls';
                } elseif (substr($str, 0x200, 4) === "\xFD\xFF\xFF\xFF" || $extname === 'ppt') {
                    $format = 'ppt';
                }
            } elseif (substr($str, 0, 4) === "PK\x03\x04") {
                if (substr($str, 0x200, 4) === "\xEC\xA5\xC1\x00" || $extname === 'docx') {
                    $format = 'docx';
                } elseif (substr($str, 0x200, 2) === "\x09\x08" || $extname === 'xlsx') {
                    $format = 'xlsx';
                } elseif (substr($str, 0x200, 4) === "\xFD\xFF\xFF\xFF" || $extname === 'pptx') {
                    $format = 'pptx';
                } else {
                    $format = 'zip';
                }
            } elseif (substr($str, 0, 4) === 'Rar!' && $extname != 'txt') {
                $format = 'rar';
            } elseif (substr($str, 0, 4) === "\x25PDF") {
                $format = 'pdf';
            } elseif (substr($str, 0, 3) === "\x30\x82\x0A") {
                $format = 'cert';
            } elseif (substr($str, 0, 4) === 'ITSF' && $extname != 'txt') {
                $format = 'chm';
            } elseif (substr($str, 0, 4) === "\x2ERMF") {
                $format = 'rm';
            } elseif ($extname === 'sql') {
                $format = 'sql';
            } elseif ($extname === 'txt') {
                $format = 'txt';
            }
        }

        if ($limit_ext_types && stristr($limit_ext_types, '|'.$format.'|') === false) {
            $format = '';
        }

        return $format;
    }

    /**
     * 对 MYSQL LIKE 的内容进行转义
     *
     * @param string      string  内容
     * @return string
     */
    public static function mysql_like_quote($str)
    {
        return strtr($str, ['\\\\' => '\\\\\\\\', '_' => '\_', '%' => '\%', "\'" => "\\\\\'"]);
    }

    /**
     * 获取服务器的ip
     *
     *
     * @return string
     **/
    public static function real_server_ip()
    {
        static $serverip = null;

        if ($serverip !== null) {
            return $serverip;
        }

        if (isset($_SERVER)) {
            if (isset($_SERVER['SERVER_ADDR'])) {
                $serverip = $_SERVER['SERVER_ADDR'];
            } else {
                $serverip = '0.0.0.0';
            }
        } else {
            $serverip = getenv('SERVER_ADDR');
        }

        return $serverip;
    }

    /**
     * 自定义 header 函数，用于过滤可能出现的安全隐患
     *
     * @param string  string  内容
     * @return void
     **/
    public static function ecs_header($string, $replace = true, $http_response_code = 0)
    {
        if (strpos($string, '../upgrade/index.php') === 0) {
            echo '<script type="text/javascript">window.location.href="'.$string.'";</script>';
        }
        $string = str_replace(["\r", "\n"], ['', ''], $string);

        if (preg_match('/^\s*location:/is', $string)) {
            @header($string."\n", $replace);

            exit();
        }

        @header($string, $replace);
    }

    public static function ecs_iconv($source_lang, $target_lang, $source_string = '')
    {
        static $chs = null;

        // 如果字符串为空或者字符串不需要转换，直接返回
        if ($source_lang === $target_lang || $source_string === '' || preg_match("/[\x80-\xFF]+/", $source_string) === 0) {
            return $source_string;
        }

        if (is_null($chs)) {
            $chs = new Chinese(ROOT_PATH);
        }

        return $chs->Convert($source_lang, $target_lang, $source_string);
    }

    public static function ecs_geoip($ip)
    {
        static $ipObj = null;

        if (is_null($ipObj)) {
            $ipObj = new IpLocation;
        }

        return $ipObj->getLocation($ip)['country'];
    }

    /**
     * 去除字符串右侧可能出现的乱码
     *
     * @param  string  $str  字符串
     * @return string
     */
    public static function trim_right($str)
    {
        $len = strlen($str);
        // 为空或单个字符直接返回
        if ($len === 0 || ord($str[$len - 1]) < 127) {
            return $str;
        }
        // 有前导字符的直接把前导字符去掉
        if (ord($str[$len - 1]) >= 192) {
            return substr($str, 0, $len - 1);
        }
        // 有非独立的字符，先把非独立字符去掉，再验证非独立的字符是不是一个完整的字，不是连原来前导字符也截取掉
        $r_len = strlen(rtrim($str, "\x80..\xBF"));
        if ($r_len === 0 || ord($str[$r_len - 1]) < 127) {
            return Str::substr($str, 0, $r_len);
        }

        $as_num = ord(~$str[$r_len - 1]);
        if ($as_num > (1 << (6 + $r_len - $len))) {
            return $str;
        } else {
            return substr($str, 0, $r_len - 1);
        }
    }

    /**
     * 将上传文件转移到指定位置
     *
     * @param  string  $file_name
     * @param  string  $target_name
     * @return blog
     */
    public static function move_upload_file($file_name, $target_name = '')
    {
        if (function_exists('move_uploaded_file')) {
            if (move_uploaded_file($file_name, $target_name)) {
                @chmod($target_name, 0755);

                return true;
            } elseif (copy($file_name, $target_name)) {
                @chmod($target_name, 0755);

                return true;
            }
        } elseif (copy($file_name, $target_name)) {
            @chmod($target_name, 0755);

            return true;
        }

        return false;
    }

    /**
     * 将JSON传递的参数转码
     *
     * @param  string  $str
     * @return string
     *
     * @deprecated 该函数已被废弃
     */
    public static function json_str_iconv($str)
    {
        if (EC_CHARSET != 'utf-8') {
            if (is_string($str)) {
                return addslashes(stripslashes(BaseHelper::ecs_iconv('utf-8', EC_CHARSET, $str)));
            } elseif (is_array($str)) {
                foreach ($str as $key => $value) {
                    $str[$key] = BaseHelper::json_str_iconv($value);
                }

                return $str;
            } elseif (is_object($str)) {
                foreach ($str as $key => $value) {
                    $str->$key = BaseHelper::json_str_iconv($value);
                }

                return $str;
            } else {
                return $str;
            }
        }

        return $str;
    }

    /**
     * 循环转码成utf8内容
     *
     * @param  string  $str
     * @return string
     *
     * @deprecated 该函数已被废弃
     */
    public static function to_utf8_iconv($str)
    {
        if (EC_CHARSET != 'utf-8') {
            if (is_string($str)) {
                return BaseHelper::ecs_iconv(EC_CHARSET, 'utf-8', $str);
            } elseif (is_array($str)) {
                foreach ($str as $key => $value) {
                    $str[$key] = BaseHelper::to_utf8_iconv($value);
                }

                return $str;
            } elseif (is_object($str)) {
                foreach ($str as $key => $value) {
                    $str->$key = BaseHelper::to_utf8_iconv($value);
                }

                return $str;
            } else {
                return $str;
            }
        }

        return $str;
    }

    /**
     * 获取文件后缀名,并判断是否合法
     *
     * @param  string  $file_name
     * @param  array  $allow_type
     * @return blob
     */
    public static function get_file_suffix($file_name, $allow_type = [])
    {
        $file_name_arr = explode('.', $file_name);
        $file_suffix = strtolower(array_pop($file_name_arr));
        if (empty($allow_type)) {
            return $file_suffix;
        } else {
            if (in_array($file_suffix, $allow_type)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 读结果缓存文件
     *
     * @params  string  $cache_name
     *
     * @return array $data
     */
    public static function read_static_cache($cache_name)
    {
        if (DEBUG_MODE) {
            return false;
        }
        static $result = [];
        if (! empty($result[$cache_name])) {
            return $result[$cache_name];
        }
        $cache_file_path = ROOT_PATH.'/temp/static_caches/'.$cache_name.'.php';
        if (file_exists($cache_file_path)) {
            include_once $cache_file_path;
            $result[$cache_name] = $data;

            return $result[$cache_name];
        } else {
            return false;
        }
    }

    /**
     * 写结果缓存文件
     *
     * @params  string  $cache_name
     * @params  string  $caches
     */
    public static function write_static_cache($cache_name, $caches)
    {
        if (DEBUG_MODE) {
            return false;
        }
        $cache_file_path = ROOT_PATH.'/temp/static_caches/'.$cache_name.'.php';
        $content = "<?php\r\n";
        $content .= '$data = '.var_export($caches, true).";\r\n";
        $content .= '?>';
        file_put_contents($cache_file_path, $content, LOCK_EX);
    }

    /**
     * 检测是否使用手机访问
     *
     * @return bool
     */
    public static function is_mobile()
    {
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap')) {
            return true;
        } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), 'VND.WAP.WML')) {
            return true;
        } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return true;
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        return false;
    }
}
