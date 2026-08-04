<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Presence;
use App\Models\Review;
use App\Models\Setting;
use Carbon\Carbon;

class AuthController extends Controller
{
    // REGISTER CEPAT (Nama, Email, Password)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Simpan User
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'member',
                ]);
                \Log::info('Email tujuan: ' . $user->email);
                // 2. Kirim Notifikasi Verifikasi Email
                try {
    $user->sendEmailVerificationNotification();

    \Log::info('Email verification berhasil dipanggil');
} catch (\Throwable $e) {
    \Log::error('Gagal kirim email: ' . $e->getMessage());

    throw $e;
}

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registrasi Berhasil! Silakan cek email untuk verifikasi.',
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal daftar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Email atau password salah!'], 401);
        }

        // Cek apakah sudah verifikasi email (Opsional untuk Flutter)
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['status' => 'error', 'message' => 'Email Anda belum diverifikasi.'], 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('android_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil!',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'member_info' => $user->member, // Akan null jika belum aktivasi/bayar
                'last_payment_status' => $user->payments()->latest()->value('status')
            ]
        ]);
    }   

    // AKTIVASI MEMBER & PEMBAYARAN (Di sini data lengkap diisi)
    public function pay(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'address' => 'required',
            'gender' => 'required|in:L,P',
            'type' => 'required|exists:prices,category',
            'student_card' => 'required_if:type,Pelajar|image|mimes:jpg,png,jpeg|max:2048',
            'proof_of_payment' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'amount' => 'required|numeric',
            'duration' => 'required|numeric' 
        ]);

        try {
            $user = $request->user();

            return DB::transaction(function () use ($request, $user) {
                // 1. Inisialisasi variabel di luar agar tidak "Undefined"
                $studentCardPath = null;
                $proofPath = null;

                // 2. Handle Upload Kartu Pelajar (Jika ada)
                if ($request->hasFile('student_card')) {
                    $file = $request->file('student_card');
                    $filename = time() . '_card_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/identitas'), $filename);
                    $studentCardPath = 'uploads/identitas/' . $filename;
                }

                // 3. Handle Upload Bukti Bayar (Wajib)
                if ($request->hasFile('proof_of_payment')) {
                    $file = $request->file('proof_of_payment');
                    $filename = time() . '_pay_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/pembayaran'), $filename);
                    $proofPath = 'uploads/pembayaran/' . $filename;
                }

                // 4. Buat atau Update data Member
                $member = $user->member()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'phone_number' => $request->phone_number,
                        'address' => $request->address,
                        'gender' => $request->gender,
                        'type' => $request->type,
                        'student_card' => $studentCardPath,
                        // Tetap ACTIVE jika memang sudah member aktif
                        'status' => ($user->member && $user->member->status == 'active') ? 'active' : 'pending',
                    ]
                );

                // 5. Simpan ke Tabel Payments dengan DURASI yang benar
                $payment = $user->payments()->create([
                    'member_id' => $member->id,
                    'amount' => $request->amount,
                    'payment_date' => now(),
                    'proof_of_payment' => $proofPath,
                    'description' => "Aktivasi/Perpanjangan member " . $request->duration . " bulan",
                    'duration' => $request->duration, // Durasi masuk ke DB
                    'status' => 'pending',
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan. Akun akan aktif/diperpanjang setelah diverifikasi admin.',
                    'data' => $payment
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal simpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPrices()
    {
        try {
            // Ambil semua data dari tabel prices
            $prices = \DB::table('prices')->get(['id', 'category', 'price', 'registration_fee', 'description']);

            return response()->json([
                'status' => 'success',
                'data' => $prices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentHistory(Request $request)
    {
        try {
            $user = $request->user();
            // Ambil riwayat pembayaran, urutkan dari yang paling baru
            $history = $user->payments()
                            ->orderBy('created_at', 'desc')
                            ->get();

            return response()->json([
                'status' => 'success',
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil riwayat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentMethod()
{
    try {
        $setting = Setting::first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'bank_name' => $setting->bank_name ?? 'Belum Diatur',
                'bank_account' => $setting->bank_account ?? 'Belum Diatur',
                // Perhatikan: Tambahkan 'storage/' sebelum memanggil path gambar
                'qris_image' => $setting->qris_image ? asset('storage/' . $setting->qris_image) : null,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal memuat metode pembayaran: ' . $e->getMessage()
        ], 500);
    }
}

    public function checkIn(Request $request) 
    {
        try {
            $user = $request->user();
            $today = date('Y-m-d');

            $already = Presence::where('user_id', $user->id)
                        ->whereDate('check_in', $today)
                        ->first();

            if ($already) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Kamu sudah absen hari ini!'
                ], 200); // Gunakan 200 agar Flutter tidak masuk ke blok catch
            }

            Presence::create([
                'user_id' => $user->id,
                'check_in' => now()
            ]);

            return response()->json([
                'status' => 'success', 
                'message' => 'Berhasil absen! Semangat latihannya!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function getPresenceStats(Request $request) 
    {
        $user = $request->user();
        // Ambil data 7 hari terakhir untuk grafik
        $stats = Presence::where('user_id', $user->id)
                    ->orderBy('check_in', 'desc')
                    ->limit(7)
                    ->get();
        return response()->json(['status' => 'success', 'data' => $stats]);
    }

    public function checkPresenceStatus(Request $request)
{
    $user = $request->user();
    $today = date('Y-m-d');

    // Cek apakah ada record presensi hari ini
    $already = Presence::where('user_id', $user->id)
                       ->whereDate('check_in', $today)
                       ->exists();

    return response()->json([
        'status' => 'success',
        'sudah_absen' => $already // Mengembalikan true jika sudah absen, false jika belum
    ]);
}

    public function storeReview(Request $request)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string',
    ]);

    try {
        $user = $request->user();

        $review = Review::create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_published' => false, // Default false, menunggu approval admin di web
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan berhasil dikirim dan menunggu verifikasi admin!',
            'data' => $review
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menyimpan ulasan: ' . $e->getMessage()
        ], 500);
    }
}

    public function profile(Request $request)
{
    $user = $request->user();
    
    return response()->json([
        'status' => 'success',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'member_info' => $user->member, // Mengambil data relasi member terbaru
            'last_payment_status' => $user->payments()->latest()->value('status')
        ]
    ]);
}

public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string',
        'gender' => 'required|in:L,P',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    try {
        $user = $request->user();
        
        // 1. Update nama di tabel users
        $user->update(['name' => $request->name]);

        // 2. Siapkan data untuk tabel members
        $memberData = [
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'gender' => $request->gender,
        ];

        // 3. Handle upload foto profil jika ada berkas baru
        if ($request->hasFile('profile_picture')) {
            $member = $user->member;
            
            // Hapus foto lama jika ada agar tidak memenuhi storage laptop
            if ($member && $member->profile_picture && File::exists(public_path($member->profile_picture))) {
                File::delete(public_path($member->profile_picture));
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_profile_' . Str::slug($user->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            
            // Simpan path ke dalam array data
            $memberData['profile_picture'] = 'uploads/profile/' . $filename;
        }

        // 4. Update atau create data di tabel members
        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            $memberData
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'member_info' => $user->member()->first(), 
                'last_payment_status' => $user->payments()->latest()->value('status')
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
        ], 500);
    }
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Token berhasil dicabut dan sesi berakhir.']);
    }

    // Reset Password Member
    // Step 1: Kirim link/OTP ke Email
    public function forgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    // 1. Generate 6 digit OTP
    $otp = rand(100000, 999999);

    // 2. Simpan ke database (menggunakan tabel bawaan Laravel)
    // token kita isi dengan OTP agar mudah diverifikasi nanti
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        [
            'token' => Hash::make($otp), // Kita hash agar aman
            'created_at' => Carbon::now()
        ]
    );

    // 3. Kirim Email Manual (Ganti 'emails.otp' dengan view email Anda)
    // Untuk testing cepat, bisa gunakan Mail::raw()
    Mail::raw("Kode verifikasi reset password Anda adalah: $otp", function ($message) use ($request) {
        $message->to($request->email)->subject('Kode Verifikasi OTP');
    });

    return response()->json(['status' => 'success', 'message' => 'OTP telah dikirim ke email Anda.']);
}

    // Step 2: Reset Password Baru
    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required', // Ini adalah OTP 6 digit dari Flutter
        'password' => 'required|min:8|confirmed',
    ]);

    // 1. Cek apakah OTP ada di DB
    $passwordReset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

    if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
        return response()->json(['status' => 'error', 'message' => 'OTP tidak valid atau kadaluarsa.'], 400);
    }

    // 2. Jika valid, update password
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // 3. Hapus token setelah berhasil
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['status' => 'success', 'message' => 'Password berhasil diubah.']);
}
}