<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewSendlistController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('view_sendlist');
        if ($action === 'list') {
            $listdb = $this->get_sendlist($request);
            $this->assign('ur_here', lang('view_sendlist'));
            $this->assign('full_page', 1);

            $this->assign('listdb', $listdb['listdb']);
            $this->assign('filter', $listdb['filter']);
            $this->assign('record_count', $listdb['record_count']);
            $this->assign('page_count', $listdb['page_count']);

            return $this->display('view_sendlist');
        }

        if ($action === 'query') {
            $listdb = $this->get_sendlist($request);
            $this->assign('listdb', $listdb['listdb']);
            $this->assign('filter', $listdb['filter']);
            $this->assign('record_count', $listdb['record_count']);
            $this->assign('page_count', $listdb['page_count']);

            $sort_flag = MainHelper::sort_flag($listdb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('view_sendlist'), '', ['filter' => $listdb['filter'], 'page_count' => $listdb['page_count']]);
        }

        if ($action === 'del') {
            $id = (int) $request->input('id');
            DB::table('email_send')->where('id', $id)->limit(1)->delete();
            $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

            return $this->sys_msg(lang('del_ok'), 0, $links);
        }

        /**
         * 批量删除
         */
        if ($action === 'batch_remove') {
            if ($request->has('checkboxes')) {
                DB::table('email_send')->whereIn('id', $request->input('checkboxes'))->delete();

                $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

                return $this->sys_msg(lang('del_ok'), 0, $links);
            } else {
                $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

                return $this->sys_msg(lang('no_select'), 0, $links);
            }
        }

        /**
         * 批量发送
         */
        if ($action === 'batch_send') {
            if ($request->has('checkboxes')) {
                $row = (array) DB::table('email_send')
                    ->whereIn('id', $request->input('checkboxes'))
                    ->orderByDesc('pri')
                    ->orderBy('last_send')
                    ->limit(1)
                    ->first();

                // 发送列表为空
                if (empty($row['id'])) {
                    $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

                    return $this->sys_msg(lang('mailsend_null'), 0, $links);
                }

                $res = DB::table('email_send')
                    ->whereIn('id', $request->input('checkboxes'))
                    ->orderByDesc('pri')
                    ->orderBy('last_send')
                    ->get();
                foreach ($res as $row) {
                    $row = (array) $row;
                    // 发送列表不为空，邮件地址为空
                    if (! empty($row['id']) && empty($row['email'])) {
                        DB::table('email_send')->where('id', $row['id'])->delete();

                        continue;
                    }

                    // 查询相关模板
                    $rt = (array) DB::table('email_template')->where('template_id', $row['template_id'])->first();

                    // 如果是模板，则将已存入email_sendlist的内容作为邮件内容
                    // 否则即是杂质，将mail_templates调出的内容作为邮件内容
                    if ($rt['type'] === 'template') {
                        $rt['template_content'] = $row['email_content'];
                    }

                    if ($rt['template_id'] && $rt['template_content']) {
                        if (BaseHelper::send_mail('', $row['email'], $rt['template_subject'], $rt['template_content'], $rt['is_html'])) {
                            // 发送成功

                            // 从列表中删除
                            DB::table('email_send')->where('id', $row['id'])->delete();
                        } else {
                            // 发送出错

                            if ($row['error'] < 3) {
                                $time = time();
                                DB::table('email_send')->where('id', $row['id'])->update(['error' => DB::raw('error + 1'), 'pri' => 0, 'last_send' => $time]);
                            } else {
                                DB::table('email_send')->where('id', $row['id'])->delete();
                            }
                        }
                    } else {
                        // 无效的邮件队列
                        DB::table('email_send')->where('id', $row['id'])->delete();
                    }
                }

                $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

                return $this->sys_msg(lang('mailsend_finished'), 0, $links);
            } else {
                $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

                return $this->sys_msg(lang('no_select'), 0, $links);
            }
        }

        /**
         * 全部发送
         */
        if ($action === 'all_send') {
            $row = (array) DB::table('email_send')->orderByDesc('pri')->orderBy('last_send')->limit(1)->first();

            // 发送列表为空
            if (empty($row['id'])) {
                $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

                return $this->sys_msg(lang('mailsend_null'), 0, $links);
            }

            $res = DB::table('email_send')->orderByDesc('pri')->orderBy('last_send')->get();
            foreach ($res as $row) {
                $row = (array) $row;
                // 发送列表不为空，邮件地址为空
                if (! empty($row['id']) && empty($row['email'])) {
                    DB::table('email_send')->where('id', $row['id'])->delete();

                    continue;
                }

                // 查询相关模板
                $rt = (array) DB::table('email_template')->where('template_id', $row['template_id'])->first();

                // 如果是模板，则将已存入email_sendlist的内容作为邮件内容
                // 否则即是杂质，将mail_templates调出的内容作为邮件内容
                if ($rt['type'] === 'template') {
                    $rt['template_content'] = $row['email_content'];
                }

                if ($rt['template_id'] && $rt['template_content']) {
                    if (BaseHelper::send_mail('', $row['email'], $rt['template_subject'], $rt['template_content'], $rt['is_html'])) {
                        // 发送成功

                        // 从列表中删除
                        DB::table('email_send')->where('id', $row['id'])->delete();
                    } else {
                        // 发送出错

                        if ($row['error'] < 3) {
                            $time = time();
                            DB::table('email_send')->where('id', $row['id'])->update(['error' => DB::raw('error + 1'), 'pri' => 0, 'last_send' => $time]);
                        } else {
                            DB::table('email_send')->where('id', $row['id'])->delete();
                        }
                    }
                } else {
                    // 无效的邮件队列
                    DB::table('email_send')->where('id', $row['id'])->delete();
                }
            }

            $links[] = ['text' => lang('view_sendlist'), 'href' => 'view_sendlist.php?act=list'];

            return $this->sys_msg(lang('mailsend_finished'), 0, $links);
        }
    }

    private function get_sendlist(Request $request)
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter['sort_by'] = $request->has('sort_by') ? trim($request->input('sort_by')) : 'pri';
            $filter['sort_order'] = $request->has('sort_order') ? trim($request->input('sort_order')) : 'DESC';

            $filter['record_count'] = DB::table('email_send AS e')
                ->leftJoin('email_template AS m', 'e.template_id', '=', 'm.template_id')
                ->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $listdb = DB::table('email_send as e')
            ->select('e.id', 'e.email', 'e.pri', 'e.error', DB::raw('FROM_UNIXTIME(e.last_send) AS last_send'), 'm.template_subject', 'm.type')
            ->leftJoin('email_template as m', 'e.template_id', '=', 'm.template_id')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(fn ($r) => (array) $r)
            ->toArray();

        $arr = ['listdb' => $listdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
