<?php

namespace App\Http\Controllers\Admin; // Tetap sesuaikan dengan namespace asli kamu

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member; // Tambahkan ini
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Buat User dengan role 'member' secara default
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member', // Pastikan kolom 'role' ada di migration users
        ]);

        // 2. Buat data Member terkait untuk profil fitnessnya (PENTING untuk TA)
        Member::create([
            'user_id' => $user->id,
            'status' => 'inactive', // Default tidak aktif sampai bayar
            'type' => 'umum',      // Default tipe member
            // Field lain seperti phone atau address bisa menyusul di profil
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Akan diarahkan ke /dashboard yang kemudian di-redirect oleh web.php ke member.dashboard
        return redirect(route('dashboard', absolute: false));
    }
}