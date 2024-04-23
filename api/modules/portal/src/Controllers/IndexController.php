<?php

declare(strict_types=1);

namespace Juling\Portal\Controllers;

use App\Foundation\Exceptions\CustomException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class IndexController extends BaseController
{
    public function index(Request $request): Renderable
    {
        try {
            $pathInfo = $request->path();

            if ($pathInfo === '/') {
                return $this->display('index');
            }

            $data = DB::table('contents')->where('segment', $pathInfo)->first();
            if (empty($data)) {
                throw new CustomException('页面没有找到');
            }

            return $this->display($data->template);
        } catch (CustomException|Throwable $e) {
            return $this->display('error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
