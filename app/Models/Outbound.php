<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    use HasFactory;
    protected $fillable = [
        'outbound_date',
        'bundle_id',
        'user_id',
        'quantity',
        'status'
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
}
