<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;

class RegionController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        define('INIT_NO_USERS', true);
        define('INIT_NO_SMARTY', true);

        header('Content-type: text/html; charset='.EC_CHARSET);

        $type = ! empty($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $parent = ! empty($_REQUEST['parent']) ? intval($_REQUEST['parent']) : 0;

        $arr['regions'] = CommonHelper::get_regions($type, $parent);
        $arr['type'] = $type;
        $arr['target'] = ! empty($_REQUEST['target']) ? stripslashes(trim($_REQUEST['target'])) : '';
        $arr['target'] = htmlspecialchars($arr['target']);

        echo json_encode($arr);
    }
}
