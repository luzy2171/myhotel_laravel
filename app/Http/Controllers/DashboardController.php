<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB Facade

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk Owner.
     */
    public function ownerDashboard()
    {
        // Statistik Kamar
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $occupancyRate = ($totalRooms > 0) ? ($occupiedRooms / $totalRooms) * 100 : 0;
        $userCount = User::count();
        $revenueToday = Transaction::whereDate('transaction_date', Carbon::today())->sum('amount');

        // (BARU) Statistik Tipe Kamar
        $roomTypes = Room::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        // Data untuk Grafik Pemasukan Bulanan
        $monthlyRevenueData = Transaction::selectRaw('SUM(amount) as total, YEAR(created_at) year, MONTH(created_at) month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->get();

        $chartLabels = [];
        $chartData = [];
        $date = Carbon::now()->subMonths(11);
        for ($i = 0; $i < 12; $i++) {
            $month = $date->format('F Y');
            $chartLabels[] = $month;
            $revenue = $monthlyRevenueData->first(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            $chartData[] = $revenue ? $revenue->total : 0;
            $date->addMonth();
        }

        return view('owner.dashboard', compact(
            'totalRooms',
            'occupiedRooms',
            'occupancyRate',
            'userCount',
            'revenueToday',
            'chartLabels',
            'chartData',
            'roomTypes' // Mengirim data tipe kamar ke view
        ));
    }

    /**
     * Menampilkan dashboard untuk Resepsionis.
     */
    public function receptionistDashboard()
    {
        $today = Carbon::today();

        $rooms = Room::with(['bookings' => function ($query) {
            $query->where('status', 'checked_in')->with('guest');
        }])->orderBy('room_number', 'asc')->get();

        $paginatedRooms = Room::orderBy('room_number', 'asc')->paginate(10);

        $checkInsToday = Booking::whereDate('check_in_date', $today)
            ->where('status', 'confirmed')
            ->with('guest', 'room')
            ->get();

        $checkOutsToday = Booking::whereDate('check_out_date', $today)
            ->where('status', 'checked_in')
            ->with('guest', 'room')
            ->get();

        return view('receptionist.dashboard', compact('rooms', 'checkInsToday', 'checkOutsToday', 'paginatedRooms'));
    }
}
