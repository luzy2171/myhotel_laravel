@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Owner</h1>

    <!-- Grid Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card Tingkat Okupansi -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Tingkat Okupansi</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ round($occupancyRate) }}%</p>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>

        <!-- Card Kamar Terisi -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Kamar Terisi</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $occupiedRooms }}</p>
            <p class="text-sm text-gray-400 mt-3">dari total {{ $totalRooms }} kamar</p>
        </div>

        <!-- Card Pemasukan Hari Ini -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Pemasukan Hari Ini</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">Rp{{ number_format($revenueToday, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-400 mt-3">Berdasarkan transaksi check-out</p>
        </div>

        <!-- Card Manajemen User -->
        <a href="{{ route('owner.users.index') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <h3 class="text-gray-500 text-sm font-medium">Manajemen User</h3>
            <div class="flex items-baseline mt-2">
                <p class="text-3xl font-bold text-gray-900">{{ $userCount }}</p>
                <p class="ml-2 text-gray-600">Total Pengguna</p>
            </div>
            <p class="text-sm text-gray-400 mt-3">Kelola akun staf Anda</p>
        </a>

    </div>

    <!-- Grid Statistik Tipe Kamar dan Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <!-- Statistik Tipe Kamar -->
        <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Tipe Kamar</h3>
            <div class="space-y-4">
                @forelse($roomTypes as $type => $total)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $type }}</span>
                        <span class="font-bold text-gray-800 bg-gray-200 px-2 py-1 rounded-full text-sm">{{ $total }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada data kamar untuk ditampilkan.</p>
                @endforelse
            </div>
        </div>

        <!-- Grafik -->
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pemasukan 12 Bulan Terakhir</h3>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pemasukan (Rp)',
                    data: @json($chartData),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

