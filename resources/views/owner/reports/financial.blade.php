@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan Keuangan</h1>

    <!-- Form Filter Tanggal -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <form method="GET" action="{{ route('owner.reports.financial') }}" class="flex flex-col sm:flex-row items-center gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filter Laporan
                </button>
                <!-- (BARU) Tombol Ekspor PDF -->
                <a href="{{ route('owner.reports.exportPdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" target="_blank">
                    Export ke PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Hasil Laporan -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold">Total Pemasukan: <span class="text-green-600">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</span></h3>
            <p class="text-sm text-gray-500">Menampilkan laporan dari tanggal {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} sampai {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        </div>
        <table class="min-w-full bg-white">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tamu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resepsionis</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">INV/{{ $transaction->booking_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->booking->guest->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">Tidak ada data transaksi pada rentang tanggal yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

