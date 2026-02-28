<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailTemplateController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('mail_template');

        /**
         * 模版列表
         */
        if ($action === 'list') {
            // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // 包含 html editor 类文件

            // 获得所有邮件模板
            $res = DB::table('email_template')
                ->select('template_id', 'template_code')
                ->where('type', 'template')
                ->get();
            $cur = null;

            foreach ($res as $row) {
                if ($cur === null) {
                    $cur = $row['template_id'];
                }

                $len = strlen($_LANG[$row['template_code']]);
                $templates[$row['template_id']] = $len < 18 ?
                    $_LANG[$row['template_code']].str_repeat('&nbsp;', (18 - $len) / 2)." [$row[template_code]]" :
                    $_LANG[$row['template_code']]." [$row[template_code]]";
            }

            $content = $this->load_template($cur);

            // 创建 html editor
            $editor = new FCKeditor('content');
            $editor->BasePath = '../includes/fckeditor/';
            $editor->ToolbarSet = 'Normal';
            $editor->Width = '100%';
            $editor->Height = '320';
            $editor->Value = $content['template_content'];
            $FCKeditor = $editor->CreateHtml();
            $this->assign('FCKeditor', $FCKeditor);
            $this->assign('tpl', $cur);
            $this->assign('cur', $cur);
            $this->assign('ur_here', lang('mail_template_manage'));
            $this->assign('templates', $templates);
            $this->assign('template', $content);
            $this->assign('full_page', 1);

            return $this->display('mail_template');
        }

        /**
         * 载入指定模版
         */
        if ($action === 'loat_template') {
            // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // 包含 html editor 类文件

            $tpl = intval($_GET['tpl']);
            $mail_type = isset($_GET['mail_type']) ? $_GET['mail_type'] : -1;

            // 获得所有邮件模板
            $res = DB::table('email_template')
                ->select('template_id', 'template_code')
                ->where('type', 'template')
                ->get();

            foreach ($res as $row) {
                $len = strlen($_LANG[$row['template_code']]);
                $templates[$row['template_id']] = $len < 18 ?
                    $_LANG[$row['template_code']].str_repeat('&nbsp;', (18 - $len) / 2)." [$row[template_code]]" :
                    $_LANG[$row['template_code']]." [$row[template_code]]";
            }

            $content = $this->load_template($tpl);

            if (($mail_type === -1 && $content['is_html'] === 1) || $mail_type === 1) {
                // 创建 html editor
                $editor = new FCKeditor('content');
                $editor->BasePath = '../includes/fckeditor/';
                $editor->ToolbarSet = 'Normal';
                $editor->Width = '100%';
                $editor->Height = '320';
                $editor->Value = $content['template_content'];
                $FCKeditor = $editor->CreateHtml();
                $this->assign('FCKeditor', $FCKeditor);

                $content['is_html'] = 1;
            } elseif ($mail_type === 0) {
                $content['is_html'] = 0;
            }

            $this->assign('tpl', $tpl);
            $this->assign('cur', $tpl);
            $this->assign('templates', $templates);
            $this->assign('template', $content);

            return $this->make_json_result($this->fetch('mail_template'));
        }

        /**
         * 保存模板内容
         */
        if ($action === 'save_template') {
            if (empty($_POST['subject'])) {
                return $this->sys_msg(lang('subject_empty'), 1, [], false);
            } else {
                $subject = trim($_POST['subject']);
            }

            if (empty($_POST['content'])) {
                return $this->sys_msg(lang('content_empty'), 1, [], false);
            } else {
                $content = trim($_POST['content']);
            }

            $type = intval($_POST['mail_type']);
            $tpl_id = intval($_POST['tpl']);

            $updated = DB::table('email_template')
                ->where('template_id', $tpl_id)
                ->update([
                    'template_subject' => str_replace("\\'\\\''", "\\'", $subject),
                    'template_content' => str_replace("\\'\\\''", "\\'", $content),
                    'is_html' => $type,
                    'last_modify' => TimeHelper::gmtime(),
                ]);

            if ($updated) {
                $link[0] = ['href' => 'mail_template.php?act=list', 'text' => lang('update_success')];

                return $this->sys_msg(lang('update_success'), 0, $link);
            } else {
                return $this->sys_msg(lang('update_failed'), 1, [], false);
            }
        }
    }

    /**
     * 加载指定的模板内容
     *
     * @param  string  $temp  邮件模板的ID
     * @return array
     */
    private function load_template($temp_id)
    {
        $row = DB::table('email_template')
            ->select('template_subject', 'template_content', 'is_html')
            ->where('template_id', $temp_id)
            ->first();
        $row = $row ? (array) $row : [];

        return $row;
    }
}
