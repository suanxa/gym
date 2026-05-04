<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use App\Repositories\MemberRepository;
use Carbon\Carbon;

class PaymentService
{
    protected $paymentRepo;
    protected $memberRepo;

    public function __construct(PaymentRepository $pRepo, MemberRepository $mRepo)
    {
        $this->paymentRepo = $pRepo;
        $this->memberRepo = $mRepo;
    }

    // Tambahkan ini untuk menghilangkan error
    public function getAllPayments()
    {
        return $this->paymentRepo->getAll();
    }

    public function verifyPayment($paymentId)
    {
        $this->paymentRepo->updateStatus($paymentId, 'verified');
        $payment = \App\Models\Payment::find($paymentId);
        
        // Sesuai laporan: Update masa aktif member setelah bayar
        if ($payment && $payment->user_id) {
            $this->memberRepo->update($payment->user_id, [
                'status' => 'active',
                'membership_expiry' => Carbon::now()->addDays(30)
            ]);
        }
    }
}