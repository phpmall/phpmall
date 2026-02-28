<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\ClipsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CollectionController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'collection_list') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            $record_count = DB::table('user_collect')
                ->where('user_id', $this->getUserId())
                ->count();

            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);
            $this->assign('pager', $pager);
            $this->assign('goods_list', ClipsHelper::get_collection_goods($this->getUserId(), $pager['size'], $pager['start']));
            $this->assign('url', ecs()->url());
            $lang_list = [
                'UTF8' => lang('charset.utf8'),
                'GB2312' => lang('charset.zh_cn'),
                'BIG5' => lang('charset.zh_tw'),
            ];
            $this->assign('lang_list', $lang_list);
            $this->assign('user_id', $this->getUserId());

            return $this->display('user_clips');
        }

        // 添加收藏商品(ajax)
        if ($action === 'collect') {
            $result = ['error' => 0, 'message' => ''];
            $goods_id = $_GET['id'];

            if (Session::get('user_id') <= 0) {
                $result['error'] = 1;
                $result['message'] = lang('login_please');

                return response()->json($result);
            } else {
                // 检查是否已经存在于用户的收藏夹
                $exists = DB::table('user_collect')
                    ->where('user_id', Session::get('user_id'))
                    ->where('goods_id', $goods_id)
                    ->exists();
                if ($exists) {
                    $result['error'] = 1;
                    $result['message'] = lang('collect_existed');

                    return response()->json($result);
                } else {
                    $res = DB::table('user_collect')->insert([
                        'user_id' => Session::get('user_id'),
                        'goods_id' => $goods_id,
                        'add_time' => TimeHelper::gmtime(),
                    ]);

                    if ($res === false) {
                        $result['error'] = 1;
                        $result['message'] = 'Insert failed';

                        return response()->json($result);
                    } else {
                        $result['error'] = 0;
                        $result['message'] = lang('collect_success');

                        return response()->json($result);
                    }
                }
            }
        }

        // 删除收藏的商品
        if ($action === 'delete_collection') {
            $collection_id = isset($_GET['collection_id']) ? intval($_GET['collection_id']) : 0;

            if ($collection_id > 0) {
                DB::table('user_collect')
                    ->where('rec_id', $collection_id)
                    ->where('user_id', $this->getUserId())
                    ->delete();
            }

            return response()->redirectTo('user.php?act=collection_list');
        }
    }
}
