<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'sku',
        'name',
        'unit_code'
    ];

    public $timestamps = false;

    public static function getProduct()
    {
        $rows = DB::select('
            select p.id, p.sku, p.name, u.name as unit_name,
            coalesce(sb.qty,0) as stock_qty
            from products p
            left join units u on u.code = p.unit_code
            left join stock_balances sb on sb.product_id = p.id
            order by p.id desc;
        ');
        return $rows;
    }

    public static function stockHistory()
    {
        $rows = DB::select('SELECT
            sm.id,
            sm.created_at,
            p.id   AS product_id,
            p.sku,
            p.name AS product_name,
            sm.qty_change,
            sm.ref_type,
            sm.ref_id,
            sm.created_by
            FROM stock_movements sm
            JOIN products p ON p.id = sm.product_id
            ORDER BY sm.created_at DESC, sm.id DESC;
        ');
        return $rows;
    }
}
