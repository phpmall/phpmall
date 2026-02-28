<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentionController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 添加关注商品
        if ($action === 'add_to_attention') {
            $rec_id = (int) $_GET['rec_id'];
            if ($rec_id) {
                DB::table('user_collect')
                    ->where('rec_id', $rec_id)
                    ->where('user_id', $this->getUserId())
                    ->update(['is_attention' => 1]);
            }

            return response()->redirectTo('user.php?act=collection_list');
        }

        // 取消关注商品
        if ($action === 'del_attention') {
            $rec_id = (int) $_GET['rec_id'];
            if ($rec_id) {
                DB::table('user_collect')
                    ->where('rec_id', $rec_id)
                    ->where('user_id', $this->getUserId())
                    ->update(['is_attention' => 0]);
            }

            return response()->redirectTo('user.php?act=collection_list');
        }
    }
}
