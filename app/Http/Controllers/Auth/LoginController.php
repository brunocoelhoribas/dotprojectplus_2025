<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Handles user authentication.
 *
 * This controller manages displaying the login form, validating user credentials,
 * logging users in, and logging them out. It includes specific logic
 * to handle legacy MD5 passwords and upgrade them to modern hashes.
 */
class LoginController extends Controller {

    /**
     * Display the user login view.
     *
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application {
        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     *
     * Validates the request, finds the user, and checks the password.
     * This method supports both modern (Bcrypt) and legacy (MD5) passwords.
     * If a legacy MD5 password is correct, it's automatically upgraded
     * to Bcrypt upon successful login.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('user_username', $credentials['username'])->first();

        // Fail early if the user does not exist
        if (!$user) {
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        $passwordCorrect = false;

        // --- Legacy MD5 Password Check & Upgrade ---
        // Checks if the password hash is 32 chars long and doesn't start with '$' (like Bcrypt).
        if (strlen($user->getAuthPassword()) === 32 && !str_starts_with($user->getAuthPassword(), '$')) {
            // If the provided password, when hashed with MD5, matches the database hash...
            if (md5($credentials['password']) === $user->getAuthPassword()) {
                $passwordCorrect = true;

                // --- Security Upgrade ---
                // The password is correct, but it's an old MD5 hash.
                // We'll upgrade it to a modern, secure hash (Bcrypt)
                // and save it back to the database.
                $user->forceFill([
                    'user_password' => Hash::make($credentials['password']),
                ])->save();
            }
        }
        // --- Modern Hash Check ---
        // If the legacy check failed or wasn't needed, check using the standard Bcrypt hash.
        else if (Hash::check($credentials['password'], $user->getAuthPassword())) {
            $passwordCorrect = true;
        }

        // If either check passed, log the user in.
        if ($passwordCorrect) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // If both checks failed, throw the validation error.
        throw ValidationException::withMessages([
            'username' => __('auth.failed'),
        ]);
    }


    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return Redirector|RedirectResponse|Application
     */
    public function destroy(Request $request): Redirector|RedirectResponse|Application {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
