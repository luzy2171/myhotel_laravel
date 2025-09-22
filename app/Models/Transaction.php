<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * (PERBAIKAN) Memberitahu Laravel untuk memperlakukan kolom ini sebagai objek tanggal.
     *
     * @var array
     */
    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    /**
     * Get the booking that owns the transaction.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who processed the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

