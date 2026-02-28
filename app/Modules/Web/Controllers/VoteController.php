<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if (! isset($_REQUEST['vote']) || ! isset($_REQUEST['options']) || ! isset($_REQUEST['type'])) {
            return response()->redirectTo('/');
        }

        $res = ['error' => 0, 'message' => '', 'content' => ''];

        $vote_id = intval($_POST['vote']);
        $options = trim($_POST['options']);
        $type = intval($_POST['type']);
        $ip_address = BaseHelper::real_ip();

        if ($this->vote_already_submited($vote_id, $ip_address)) {
            $res['error'] = 1;
            $res['message'] = lang('vote_ip_same');
        } else {
            $this->save_vote($vote_id, $ip_address, $options);

            $vote = MainHelper::get_vote($vote_id);
            if (! empty($vote)) {
                $this->assign('vote_id', $vote['id']);
                $this->assign('vote', $vote['content']);
            }

            $str = $this->fetch('web::library/vote');

            $pattern = '/(?:<(\w+)[^>]*> .*?)?<div\s+id="ECS_VOTE">(.*)<\/div>(?:.*?<\/\1>)?/is';

            if (preg_match($pattern, $str, $match)) {
                $res['content'] = $match[2];
            }
            $res['message'] = lang('vote_success');
        }

        return response()->json($res);
    }

    /**
     * 检查是否已经提交过投票
     *
     * @param  int  $vote_id
     * @param  string  $ip_address
     * @return bool
     */
    private function vote_already_submited($vote_id, $ip_address)
    {
        return DB::table('vote_log')
            ->where('ip_address', $ip_address)
            ->where('vote_id', $vote_id)
            ->exists();
    }

    /**
     * 保存投票结果信息
     *
     * @param  int  $vote_id
     * @param  string  $ip_address
     * @param  string  $option_id
     * @return void
     */
    private function save_vote($vote_id, $ip_address, $option_id)
    {
        DB::table('vote_log')->insert([
            'vote_id' => $vote_id,
            'ip_address' => $ip_address,
            'vote_time' => TimeHelper::gmtime(),
        ]);

        // 更新投票主题的数量
        DB::table('vote')
            ->where('vote_id', $vote_id)
            ->increment('vote_count');

        // 更新投票选项的数量
        DB::table('vote_option')
            ->whereIn('option_id', explode(',', $option_id))
            ->increment('option_count');
    }
}
