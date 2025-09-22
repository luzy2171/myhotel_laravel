<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Memastikan hanya user yang sudah login yang bisa mengakses controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman home.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Mengarahkan pengguna ke dashboard yang sesuai berdasarkan role mereka.
     * Ini adalah logika yang dipindahkan dari web.php.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('Owner')) {
            return redirect()->route('owner.dashboard');
        }

        if ($user->hasRole('Resepsionis')) {
            return redirect()->route('receptionist.dashboard');
        }

        // Fallback untuk pengguna yang mungkin tidak memiliki role
        return redirect()->route('home');
    }
}

