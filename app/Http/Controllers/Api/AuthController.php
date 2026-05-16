<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Presence;
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

                // 2. Kirim Notifikasi Verifikasi Email
                $user->sendEmailVerificationNotification();

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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => 'success', 'message' => 'Berhasil keluar.']);
    }
}