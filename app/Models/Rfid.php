<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rfid extends Model
{

    protected $table = 'rfid_tags';

    protected $fillable = [
        'epc',
        'product_id',
        'status',
        'state'
    ];

    public $timestamps = false;

    public static function getRfidTags()
    {
        $rows = DB::select('
            SELECT r.id, r.epc, p.name AS product_name, r.qty, r.status, r.state
            FROM rfid_tags r
            LEFT JOIN products p ON r.product_id = p.id
            ORDER BY r.id DESC
        ');
        return $rows;
    }
}
