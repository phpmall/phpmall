<?php

declare(strict_types=1);

namespace App\Portal\Http\Controllers\Auth;

use App\Portal\Http\Controllers\BaseController;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends BaseController
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Renderable
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : $this->display('auth.verify-email');
    }
}
