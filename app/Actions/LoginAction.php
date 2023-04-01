<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Illuminate\Validation\ValidationException;

class LoginAction extends Action
{
    public function authorize(Request $request): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function execute(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        return LoginAction::execute($request);
    }

}
