<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use App\Models\Payment;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $query = Payment::with(['user.member']);

        // Filter berdasarkan nama (member atau guest)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('external_customer_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

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

    public function export(Request $request)
    {
        return Excel::download(
            new PaymentsExport($request),
            'laporan-kas-masuk-' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}