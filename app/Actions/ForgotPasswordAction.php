<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use App\Actions\ValidationException;
use Lorisleiva\Actions\Action;
use Illuminate\Http\Request;

class ForgotPasswordAction extends Action
{
    public function execute($input)
    {
        $request = $input;
        $request->validate([
            'email' => ['required', 'email'],
        ]);

  
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgot(Request $request)
    {
        return ForgotPasswordAction::execute($request);
    }

}
