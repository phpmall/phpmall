<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\ClipsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 标签云列表
        if ($action === 'tag_list') {
            $this->assign('tags', ClipsHelper::get_user_tags($this->getUserId()));
            $this->assign('tags_from', 'user');

            return $this->display('user_clips');
        }

        // 添加标签(ajax)
        if ($action === 'add_tag') {
            $result = ['error' => 0, 'message' => '', 'content' => ''];
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $tag = isset($_POST['tag']) ? BaseHelper::json_str_iconv(trim($_POST['tag'])) : '';

            if ($this->getUserId() === 0) {
                // 用户没有登录
                $result['error'] = 1;
                $result['message'] = lang('tag_anonymous');
            } else {
                ClipsHelper::add_tag($id, $tag); // 添加tag
                $this->clear_cache_files('goods'); // 删除缓存

                // 重新获得该商品的所有缓存
                $arr = MainHelper::get_tags($id);

                foreach ($arr as $row) {
                    $result['content'][] = ['word' => htmlspecialchars($row['tag_words']), 'count' => $row['tag_count']];
                }
            }

            return json_encode($result);
        }

        // 删除标签云的处理
        if ($action === 'act_del_tag') {
            $tag_words = isset($_GET['tag_words']) ? trim($_GET['tag_words']) : '';
            ClipsHelper::delete_tag($tag_words, $this->getUserId());

            return response()->redirectTo('user.php?act=tag_list');
        }
    }
}
