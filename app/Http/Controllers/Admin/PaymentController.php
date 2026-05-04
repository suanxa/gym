<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = $this->paymentService->getAllPayments();
        return view('admin.payments.index', compact('payments'));
    }

    public function verify($id)
    {
        // Memanggil service untuk verifikasi dan otomatis update expired date member
        $this->paymentService->verifyPayment($id);
        return redirect()->back()->with('success', 'Pembayaran diverifikasi, membership otomatis diperbarui.');
    }

    public function store_manual(Request $request)
    {
        $request->validate([
            'external_customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        Payment::create([
            'user_id' => null, // NULL karena bukan member
            'external_customer_name' => $request->external_customer_name,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'approved', // Langsung approved karena diinput admin (tunai)
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Transaksi non-member berhasil dicatat!');
    }
}