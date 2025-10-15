<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller {

    public function create(): Factory|View {
        return view('auth.login');
    }


    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('user_username', $credentials['username'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        $passwordCorrect = false;

        if (strlen($user->getAuthPassword()) === 32 && !str_starts_with($user->getAuthPassword(), '$')) {
            if (md5($credentials['password']) === $user->getAuthPassword()) {
                $passwordCorrect = true;
                $user->forceFill([
                    'user_password' => Hash::make($credentials['password']),
                ])->save();
            }
        } else {
            if (Hash::check($credentials['password'], $user->getAuthPassword())) {
                $passwordCorrect = true;
            }
        }

        if ($passwordCorrect) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'username' => __('auth.failed'),
        ]);
    }


    public function destroy(Request $request): Redirector|RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
