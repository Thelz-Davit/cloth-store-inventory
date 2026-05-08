<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'code',
        'name',
        'symbol',
    ];

    public $timestamps = false;

    public static function getUnit()
    {
        $rows = DB::select('SELECT * FROM units ORDER BY id DESC');
        return $rows;
    }
}
