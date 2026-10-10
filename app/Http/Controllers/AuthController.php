<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetInquiry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('feed');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $credentials['login'];
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $remember = $request->boolean('remember');

        if (Auth::attempt([$fieldType => $loginInput, 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('feed'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // Fallback for legacy sha256 passwords from old database if any exists
        $user = User::where($fieldType, $loginInput)->first();
        if ($user && $user->password === hash('sha256', $credentials['password'])) {
            $user->password = Hash::make($credentials['password']);
            $user->save();

            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->intended(route('feed'))->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'login' => 'Username atau password yang dimasukkan salah.',
        ])->onlyInput('login');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('feed');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'hobby' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'born_date' => ['nullable', 'date'],
        ]);

        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatarName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $avatarName);
        }

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => $validated['email'] ?? strtolower($validated['username']) . '@alunaconnect.local',
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'avatar' => $avatarName,
            'hobby' => $validated['hobby'] ?? null,
            'city' => $validated['city'] ?? null,
            'born_date' => $validated['born_date'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('feed')->with('success', 'Pendaftaran berhasil! Selamat datang di Aluna Connect.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    public function showForgot(): View
    {
        return view('auth.forgot');
    }

    public function forgot(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        PasswordResetInquiry::create($validated);

        return back()->with('success', 'Permintaan pemulihan akun telah dikirim ke Administrator. Kami akan segera menghubungi email Anda.');
    }
}
