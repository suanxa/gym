<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Price;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman form pembayaran
     */
    public function create()
    {
        $member = Member::firstOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'inactive', 'type' => 'umum']
        );

        $priceSettings = Price::all()->keyBy('category');

        if ($priceSettings->isEmpty()) {
            return redirect()->route('member.dashboard')->with('error', 'Data harga belum diatur oleh admin.');
        }

        return view('member.payments.create', compact('member', 'priceSettings'));
    }

    /**
     * Menyimpan data member dan bukti pembayaran
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();
        
        // Ambil status pembayaran terakhir
        $lastPayment = $member->payments()->latest()->first();
        $isRejected = ($lastPayment && $lastPayment->status == 'rejected');

        /**
         * LOGIKA KRUSIAL:
         * Syarat 'FirstTime' (Wajib isi profil) HANYA berlaku jika:
         * 1. Nomor HP masih kosong (User baru)
         * 2. ATAU Ditolak TAPI status member masih 'inactive' (Berarti gagal di pendaftaran pertama kali)
         */
        $isFirstTime = empty($member->phone_number) || ($isRejected && $member->status == 'inactive');

        // 1. Validasi Dasar (Selalu Wajib)
        $rules = [
            'amount'           => 'required|numeric',
            'duration'         => 'required|integer|in:1,3,6,12',
            'proof_of_payment' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // 2. Validasi Profil (HANYA jika pendaftaran awal/gagal di awal)
        if ($isFirstTime) {
            $rules['phone_number']  = 'required|string|max:15';
            $rules['date_of_birth'] = 'required|date';
            $rules['gender']        = 'required|in:L,P';
            $rules['type']          = 'required|in:umum,pelajar';
            $rules['address']       = 'required|string';
            $rules['student_card']  = 'required_if:type,pelajar|nullable|image|max:2048';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // A. Upload Bukti Pembayaran
            $proofPath = $request->file('proof_of_payment')->store('payments/proofs', 'public');

            $updateData = [];
            
            // B. Update Data Profil (Hanya jika isFirstTime)
            if ($isFirstTime) {
                $updateData['phone_number']  = $request->phone_number;
                $updateData['address']       = $request->address;
                $updateData['date_of_birth'] = $request->date_of_birth;
                $updateData['gender']        = $request->gender;
                $updateData['type']          = $request->type;

                if ($request->hasFile('student_card')) {
                    if ($member->student_card) Storage::disk('public')->delete($member->student_card);
                    $updateData['student_card'] = $request->file('student_card')->store('members/student_cards', 'public');
                }
            }

            // Status member hanya jadi 'pending' jika dia tidak sedang 'active'
            // Member yang sedang perpanjang (active) tetap 'active' statusnya
            if ($member->status !== 'active') {
                $updateData['status'] = 'pending';
            }

            $member->update($updateData);

            // C. Simpan ke Tabel Payments
            Payment::create([
                'user_id'          => $user->id,
                'member_id'        => $member->id,
                'amount'           => $request->amount,
                'duration'         => $request->duration,
                'proof_of_payment' => $proofPath,
                'status'           => 'pending', 
                'payment_date'     => now(),
                'description'      => ($isFirstTime ? 'Pendaftaran Member ' : 'Perpanjangan Member ') . ucfirst($member->type) . " ({$request->duration} Bulan)",
            ]);

            DB::commit();
            
            $msg = $isFirstTime ? 'Pendaftaran berhasil dikirim!' : 'Bukti perpanjangan berhasil dikirim ulang!';
            return redirect()->route('member.dashboard')->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollback();
            if (isset($proofPath)) Storage::disk('public')->delete($proofPath);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}