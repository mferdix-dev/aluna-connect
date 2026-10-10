<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(string $username): View
    {
        $user = User::withCount(['posts', 'comments', 'likes'])
            ->where('username', strtolower($username))
            ->firstOrFail();

        $posts = $user->posts()
            ->with(['user', 'comments', 'likes'])
            ->withCount(['comments', 'likes'])
            ->paginate(10);

        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'hobby' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'born_date' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        $user->hobby = $validated['hobby'] ?? null;
        $user->city = $validated['city'] ?? null;
        $user->born_date = $validated['born_date'] ?? null;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatarName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $avatarName);

            if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar)) && $user->avatar !== 'app_icon.png') {
                @unlink(public_path('uploads/avatars/' . $user->avatar));
            }

            $user->avatar = $avatarName;
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.show', $user->username)->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
