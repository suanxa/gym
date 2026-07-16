<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\WelcomeController;

// Import Controller Admin
use App\Http\Controllers\Admin\MemberController as AdminMember;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ExpenseController as AdminExpense;
use App\Http\Controllers\Admin\ReviewController as AdminReview;
use App\Http\Controllers\Admin\PriceController as AdminPrice; 
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Admin\AdminUserController as AdminUser;

// Import Controller Member
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use App\Http\Controllers\Member\PaymentController as MemberPayment;
use App\Http\Controllers\Member\PresenceController as MemberPresence;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard Redirector (Pintu Masuk Utama Setelah Login)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'member') {
        return redirect()->route('member.dashboard');
    }
    abort(403, 'Role tidak dikenali.');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin Routes (Prefix: /admin, Name: admin.*)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Kelola Member (Tabel 3.2 Laporan)
    Route::get('/members', [AdminMember::class, 'index'])->name('members.index');
    Route::patch('/members/{id}/status', [AdminMember::class, 'updateStatus'])->name('members.status');
    Route::delete('/members/{id}', [AdminMember::class, 'destroy'])->name('members.destroy');

    // Konfirmasi Pembayaran (Tabel 3.4 Laporan)
    Route::get('/payments', [AdminPayment::class, 'index'])->name('payments.index');
    Route::post('/payments/{id}/verify', [AdminPayment::class, 'verify'])->name('payments.verify');
    Route::post('/members/approve/{id}', [AdminMember::class, 'approve'])->name('admin.members.approve');
    Route::post('/payments/manual', [AdminPayment::class, 'store_manual'])->name('payments.store_manual');
    Route::post('/members/reject/{id}', [AdminMember::class, 'reject'])->name('admin.members.reject');

    // --- MENU BARU: KELOLA HARGA ---
    Route::get('/prices', [AdminPrice::class, 'index'])->name('payments.harga');
    Route::put('/prices/{id}', [AdminPrice::class, 'update'])->name('prices.update');

    // Catatan Pengeluaran Fasilitas (Tabel 3.7 Laporan)
    Route::get('/expenses', [AdminExpense::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [AdminExpense::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{id}', [AdminExpense::class, 'destroy'])->name('expenses.destroy');

    // Kelola Review Landing Page
    Route::get('/reviews', [AdminReview::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{id}/publish', [AdminReview::class, 'togglePublish'])->name('reviews.publish');

    // --- MENU BARU: SETTING WEBSITE ---
    Route::get('/settings', [AdminSetting::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSetting::class, 'update'])->name('settings.update');

    Route::get('/users', [AdminUser::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUser::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [AdminUser::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| Member Routes (Prefix: /member, Name: member.*)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class])->prefix('member')->name('member.')->group(function () {
    
    // Dashboard Utama Member
    Route::get('/dashboard', [MemberDashboard::class, 'index'])->name('dashboard');

    // Pembayaran Iuran (Upload Bukti)
    Route::get('/payments/create', [MemberPayment::class, 'create'])->name('payments.create');
    Route::post('/payments', [MemberPayment::class, 'store'])->name('payments.store');

    // Riwayat Kehadiran (Dikelola oleh PresenceController)
    Route::get('/presences', [MemberPresence::class, 'index'])->name('presences.index');
    
    // Route untuk Proses Klik Tombol Absen
    Route::post('/presences', [MemberPresence::class, 'store'])->name('presences.store');
});

/*
|--------------------------------------------------------------------------
| Common Routes (Profile & Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google Socialite
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| Email Verification Routes (Custom Landing Page)
|--------------------------------------------------------------------------
*/

// Rute ini menangani klik link dari email user
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    // 1. Cari user berdasarkan ID dari link
    $user = User::find($id);

    \Log::info([
        'id' => $id,
        'email' => $user?->email,
        'hash_url' => $hash,
        'hash_db' => $user ? sha1($user->getEmailForVerification()) : null,
        'hasValidSignature' => $request->hasValidSignature(),
    ]);

    // 2. Validasi apakah user ada dan hash-nya cocok
    if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link verifikasi tidak valid.');
    }

    // 3. Jika belum diverifikasi, proses verifikasi
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    // 4. Tampilkan halaman sukses buatanmu
    return view('auth.verified'); 
})->middleware(['signed'])->name('verification.verify');

require __DIR__.'/auth.php';