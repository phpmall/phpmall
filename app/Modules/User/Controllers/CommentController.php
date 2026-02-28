<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Bundles\Comment\Entities\CommentEntity;
use App\Helpers\ClipsHelper;
use App\Helpers\MainHelper;
use App\Services\Comment\CommentService;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $commentService = new CommentService;

        if ($action === 'comment_list') {
            $page = intval($request->get('page', 1));

            // 获取用户留言的数量
            $record_count = $commentService->count([
                CommentEntity::getParentId => 0,
                CommentEntity::getUserId => $this->getUserId(),
            ]);
            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page, 5);

            $this->assign('comment_list', ClipsHelper::get_comment_list($this->getUserId(), $pager['size'], $pager['start']));
            $this->assign('pager', $pager);

            return $this->display('user_clips');
        }

        // 删除评论
        if ($action === 'del_cmt') {
            $id = intval($request->get('id', 0));
            if ($id > 0) {
                $commentService->remove([
                    CommentEntity::getCommentId => $id,
                    CommentEntity::getUserId => $this->getUserId(),
                ]);
            }

            return response()->redirectTo('user.php?act=comment_list');
        }
    }
}
