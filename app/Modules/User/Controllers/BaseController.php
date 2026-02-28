<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\UserServiceProvider;
use Illuminate\Support\Facades\Auth;

abstract class BaseController extends Controller
{
    protected function getUserId(): int
    {
        return Auth::guard(UserServiceProvider::NS)->user()->getAuthIdentifier();
    }
}
