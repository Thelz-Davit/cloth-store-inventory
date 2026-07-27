<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    // TAMBAHKAN INI BIAR SEMUA KOLOM AMAN DI-INPUT
    protected $guarded = [];

    /**
     * Relasi ke Model User (Staf/Admin yang mencatat Outbound)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Model Bundle (Paket yang dikirim)
     */
    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id');
    }
}