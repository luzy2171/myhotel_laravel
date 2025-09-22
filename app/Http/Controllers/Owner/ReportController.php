<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF Facade

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan keuangan dengan filter.
     */
    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with('booking.guest', 'user')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('amount');

        return view('owner.reports.financial', compact('transactions', 'totalRevenue', 'startDate', 'endDate'));
    }

    /**
     * (BARU) Mengekspor laporan keuangan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with('booking.guest', 'user')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('amount');

        $data = [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        $pdf = PDF::loadView('owner.reports.pdf', $data);
        return $pdf->download('laporan-keuangan-' . $startDate . '-sampai-' . $endDate . '.pdf');
    }
}

