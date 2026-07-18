<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithStyles
{
    protected $request;
    protected $totalAmount = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function baseQuery()
    {
        $query = Payment::with(['user.member']);

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('external_customer_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }
        if ($this->request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $this->request->end_date);
        }
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query;
    }

    public function collection()
    {
        $data = $this->baseQuery()->latest()->get();
        $this->totalAmount = $data->sum('amount');
        return $data;
    }

    public function headings(): array
    {
        return ['No', 'Waktu Transaksi', 'Nama Pelanggan', 'Tipe Anggota', 'Deskripsi', 'Nominal', 'Status'];
    }

    public function map($payment): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $payment->created_at->format('d/m/Y H:i'),
            $payment->user->name ?? $payment->external_customer_name,
            $payment->user_id ? ucfirst($payment->user->member?->type ?? 'Umum') : 'Guest',
            $payment->description ?? 'Iuran Membership',
            $payment->amount,
            $payment->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $totalRow = $lastRow + 2;

                $sheet->setCellValue("E{$totalRow}", 'TOTAL PENDAPATAN');
                $sheet->setCellValue("F{$totalRow}", $this->totalAmount);

                $sheet->getStyle("E{$totalRow}:F{$totalRow}")->getFont()->setBold(true);
                $sheet->getStyle("F{$totalRow}")->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Format kolom nominal (F) jadi angka ribuan
                $sheet->getStyle("F2:F{$lastRow}")->getNumberFormat()
                    ->setFormatCode('#,##0');

                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}