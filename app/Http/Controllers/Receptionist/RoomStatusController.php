<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomStatusController extends Controller
{
    /**
     * Menampilkan daftar kamar untuk manajemen status.
     */
    public function index()
    {
        $rooms = Room::latest()->paginate(15);
        return view('receptionist.rooms.index', compact('rooms'));
    }

    /**
     * Memperbarui status sebuah kamar.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            // Resepsionis hanya boleh mengubah ke status operasional harian
            'status' => 'required|in:available,occupied,cleaning',
        ]);

        $room->status = $request->status;
        $room->save();

        return back()->with('success', 'Status Kamar ' . $room->room_number . ' berhasil diperbarui.');
    }
}
