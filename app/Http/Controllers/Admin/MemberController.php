<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MemberService;
use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index()
    {
        // Mengambil member yang memiliki pembayaran 'pending' diprioritaskan ke atas
        // Kita cek melalui relasi payments yang statusnya pending
        $members = Member::with(['user', 'payments' => function($query) {
                $query->latest();
            }])
            ->addSelect(['latest_payment_status' => Payment::select('status')
                ->whereColumn('member_id', 'members.id')
                ->latest()
                ->take(1)
            ])
            // Urutkan: Yang punya status pending di pembayaran terakhir muncul paling atas
            ->orderByRaw("CASE WHEN latest_payment_status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->get();

        return view('admin.members.index', compact('members'));
    }

public function approve($id)
{
    $member = Member::findOrFail($id);
    // Ambil pembayaran pending terakhir
    $payment = Payment::where('member_id', $member->id)
                      ->where('status', 'pending')
                      ->latest()
                      ->first();

    if (!$payment) {
        return redirect()->back()->with('error', 'Data pembayaran pending tidak ditemukan.');
    }

    $now = now();
    // Pastikan mengambil duration dari tabel payments
    $durationMonths = $payment->duration; 

    // Logika Akumulasi Tanggal Expiry
    // Jika masih aktif (expiry > now), kita tambah dari tanggal expiry lama
    // Jika sudah mati (expiry < now atau null), kita tambah dari hari ini
    $currentExpiry = $member->membership_expiry ? Carbon::parse($member->membership_expiry) : null;
    
    $startDate = ($currentExpiry && $currentExpiry->gt($now)) ? $currentExpiry : $now;
    $newExpiry = $startDate->addMonths($durationMonths);

    $member->update([
        'status' => 'active',
        'membership_expiry' => $newExpiry
    ]);

    // Update status pembayaran menjadi approved/success
    $payment->update(['status' => 'approved']);

    return redirect()->back()->with('success', "Pembayaran disetujui. Masa aktif member {$member->user->name} bertambah {$durationMonths} bulan.");
}

public function reject($id)
{
    $member = Member::findOrFail($id);
    $now = now();

    $payment = Payment::where('member_id', $member->id)
                      ->where('status', 'pending')
                      ->latest()
                      ->first();

    if ($payment) {
        $payment->update(['status' => 'rejected']);
    }

    // LOGIKA KEAMANAN STATUS:
    // Cek apakah sebenarnya dia masih punya masa aktif yang sah?
    $isStillActive = $member->membership_expiry && Carbon::parse($member->membership_expiry)->gt($now);

    // HANYA ubah ke inactive jika masa aktifnya memang sudah habis atau dia pendaftaran baru
    if (!$isStillActive) {
        $member->update(['status' => 'inactive']);
    } 
    // Jika $isStillActive bernilai TRUE, status member dibiarkan 'active' 
    // agar dia tetap bisa masuk gym meski perpanjangannya ditolak.

    return redirect()->back()->with('success', 'Pembayaran ditolak. Member tetap pada status sebelumnya.');
}

    public function updateStatus(Request $request, $id)
    {
        $this->memberService->updateStatus($id, $request->status);
        return redirect()->back()->with('success', 'Status operasional member berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Pastikan file kartu pelajar dan bukti bayar ikut terhapus di Service jika perlu
        $this->memberService->deleteMember($id);
        return redirect()->back()->with('success', 'Seluruh data member berhasil dihapus.');
    }
}