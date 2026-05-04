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
        $payment = Payment::where('member_id', $member->id)->where('status', 'pending')->latest()->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Data pembayaran pending tidak ditemukan.');
        }

        $now = now();
        $durationMonths = $payment->duration ?? 1; 

        // Logika Akumulasi: Jika belum expired, tambah dari tanggal expiry. Jika sudah, tambah dari sekarang.
        $currentExpiry = $member->membership_expiry ? Carbon::parse($member->membership_expiry) : $now;
        $startDate = $currentExpiry->gt($now) ? $currentExpiry : $now;
        $newExpiry = $startDate->addMonths($durationMonths);

        $member->update([
            'status' => 'active',
            'membership_expiry' => $newExpiry
        ]);

        $payment->update(['status' => 'approved']);

        return redirect()->back()->with('success', "Pembayaran disetujui. Masa aktif bertambah $durationMonths bulan.");
    }

    /**
     * Menolak Pembayaran (Graceful Reject)
     */
    public function reject($id)
    {
        $member = Member::findOrFail($id);
        $now = now();

        // 1. Ambil pembayaran pending terakhir
        $payment = Payment::where('member_id', $member->id)
                          ->where('status', 'pending')
                          ->latest()
                          ->first();

        // 2. Cek apakah member sebenarnya masih punya masa aktif sah dari pembayaran sebelumnya?
        $isStillActive = $member->membership_expiry && Carbon::parse($member->membership_expiry)->gt($now);

        // Jika pendaftaran awal (belum ada hp) atau masa aktif sudah habis, kembalikan ke inactive
        // Jika dia member aktif yang sedang perpanjang tapi buktinya salah, statusnya tetap active (tidak rugi)
        if (!$isStillActive || empty($member->phone_number)) {
            $member->update(['status' => 'inactive']);
        }

        // 3. Update status pembayaran menjadi Rejected
        if ($payment) {
            $payment->update(['status' => 'rejected']);
        }

        return redirect()->back()->with('success', 'Pembayaran ditolak. Member diberitahukan untuk upload ulang.');
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