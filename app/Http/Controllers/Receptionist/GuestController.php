<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Menampilkan daftar tamu dengan fungsionalitas pencarian.
     */
    public function index(Request $request)
    {
        $query = Guest::query()->orderBy('name', 'asc');

        // --- LOGIKA PENCARIAN BARU ---
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            // Mencari di beberapa kolom sekaligus
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('identity_number', 'like', '%' . $search . '%')
                  ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }
        // --- AKHIR LOGIKA PENCARIAN ---

        $guests = $query->paginate(10)->withQueryString(); // withQueryString() agar filter tetap ada saat pindah halaman

        return view('receptionist.guests.index', compact('guests'));
    }

    // Metode lain (create, store, edit, update, destroy) tidak berubah
    // ...
    public function create()
    {
        return view('receptionist.guests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:50|unique:guests,identity_number',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        Guest::create($request->all());

        return redirect()->route('receptionist.guests.index')->with('success', 'Tamu baru berhasil ditambahkan.');
    }

    public function edit(Guest $guest)
    {
        return view('receptionist.guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'identity_number' => 'required|string|max:50|unique:guests,identity_number,'.$guest->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $guest->update($request->all());

        return redirect()->route('receptionist.guests.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->route('receptionist.guests.index')->with('success', 'Data tamu berhasil dihapus.');
    }
}

