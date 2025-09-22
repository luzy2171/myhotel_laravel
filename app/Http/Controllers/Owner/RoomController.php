<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::latest();
        if ($request->has('search') && $request->search != '') {
            $query->where('room_number', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
        }
        $rooms = $query->paginate(10);
        return view('owner.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // --- PENYEBAB MASALAH KEMUNGKINAN BESAR ADA DI SINI ---
        // Laravel akan memeriksa data sebelum menyimpan.
        $request->validate([
            // Aturan ini mengharuskan nomor kamar unik (tidak boleh ada yang sama di database).
            'room_number' => 'required|string|max:10|unique:rooms,room_number',
            'type' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
        ]);

        // Jika validasi lolos, data akan disimpan.
        Room::create([
            'room_number' => $request->room_number,
            'type' => $request->type,
            'price_per_night' => $request->price_per_night,
        ]);

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Kamar baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('owner.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|string|max:10|unique:rooms,room_number,' . $room->id,
            'type' => 'required|string|max:255',
            'price_per_night' => 'required|numeric|min:0',
        ]);

        $room->update($request->all());

        return redirect()->route('owner.rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('owner.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    /**
     * Update room status manually.
     */
    public function updateStatus(Request $request, Room $room)
    {
        $request->validate(['status' => 'required|in:available,occupied,maintenance,cleaning']);
        $room->update(['status' => $request->status]);
        return back()->with('success', 'Status kamar ' . $room->room_number . ' berhasil diperbarui.');
    }
}

