<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
            'is_active' => 1,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = DB::selectOne("
            SELECT u.id, u.name, u.email, u.role_id, r.role_name
            FROM users u
            JOIN roles r ON r.role_id = u.role_id
            WHERE u.id = ?
        ", [Auth::id()]);

        session([
            'role_id'   => $user->role_id,
            'role_name' => $user->role_name,
        ]);

        return redirect()->intended('/')
            ->with('toast_success', 'Login berhasil');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
