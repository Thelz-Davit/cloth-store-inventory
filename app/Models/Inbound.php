<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inbound extends Model
{
    protected $table = 'inbounds';

    protected $fillable = [
        'inbound_no',
        'received_at',
        'user_id'
    ];

    public $timestamps = false;

    public static function getInBound()
    {
        $draftIds = session()->get('inbound_draft', []);

        if (empty($draftIds)) {
            return [];
        }

        $draftIds = array_map('intval', $draftIds);
        $inClause = implode(',', $draftIds);

        $rows = DB::select("
            select rt.id, rt.epc, rt.status, rt.state, p.sku as product_sku, p.name as product_name
            from rfid_tags rt
            left join products p on rt.product_id = p.id
            where rt.id in ($inClause)
        ");

        return $rows;
    }
}
