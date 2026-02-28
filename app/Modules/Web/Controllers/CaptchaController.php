<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $img = new captcha(ROOT_PATH.'data/captcha/', cfg('captcha_width'), cfg('captcha_height'));
        @ob_end_clean(); // 清除之前出现的多余输入
        if (isset($_REQUEST['is_login'])) {
            $img->session_word = 'captcha_login';
        }
        $img->generate_image();
    }
}
