<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresenceController extends Controller
{
    /**
     * Menampilkan halaman riwayat kehadiran
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil data profil member
        $member = Member::where('user_id', $userId)->first();

        // 2. Ambil semua riwayat kehadiran member ini (Urutkan dari yang terbaru)
        $presences = Presence::where('user_id', $userId)
            ->latest('check_in')
            ->get();

        // 3. Logika pengecekan apakah sudah absen hari ini
        // Digunakan untuk mengubah tampilan tombol di Blade secara otomatis
        $hasCheckedInToday = Presence::where('user_id', $userId)
            ->whereDate('check_in', Carbon::today())
            ->exists();

        return view('member.presences.index', compact('presences', 'member', 'hasCheckedInToday'));
    }

    /**
     * Proses simpan absensi (Hanya 1x sehari)
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        // Ambil data member untuk pengecekan status
        $member = Member::where('user_id', $userId)->first();

        // A. VALIDASI: Status harus ACTIVE
        if (!$member || $member->status !== 'active') {
            return back()->with('error', 'Maaf, akses ditolak. Status keanggotaan Anda belum aktif.');
        }

        // B. VALIDASI: Cek Masa Berlaku (Expiry Date)
        if ($member->membership_expiry && Carbon::now()->gt(Carbon::parse($member->membership_expiry))) {
            // Jika sudah lewat tanggal, otomatis ubah status jadi inactive
            $member->update(['status' => 'inactive']);
            return back()->with('error', 'Masa aktif membership Anda sudah habis. Silakan lakukan perpanjangan.');
        }

        // C. VALIDASI: Cek apakah sudah absen hari ini (Double Check untuk keamanan database)
        $alreadyCheckedIn = Presence::where('user_id', $userId)
            ->whereDate('check_in', Carbon::today())
            ->exists();

        if ($alreadyCheckedIn) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Sampai jumpa besok!');
        }

        try {
            // D. EKSEKUSI: Simpan data kehadiran
            Presence::create([
                'user_id' => $userId,
                'check_in' => Carbon::now(),
            ]);

            return back()->with('success', 'Berhasil! Selamat berlatih, semangat olahraganya!');

        } catch (\Exception $e) {
            // Jika terjadi error sistem (misal database down)
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }
}