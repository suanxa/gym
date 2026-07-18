<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        // Hanya menampilkan user dengan role admin
        $admins = User::where('role', 'admin')->get();
        return view('admin.users.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Memastikan role selalu admin
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function destroy(User $user)
    {
        // Mencegah admin menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'Akun admin berhasil dihapus.');
    }
}
