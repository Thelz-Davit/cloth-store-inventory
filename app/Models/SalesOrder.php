<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesOrder extends Model
{
    public static function getOrders()
    {
        return DB::select("
            SELECT
                so.id, so.order_no, so.customer_name, so.order_date, so.status,
                COUNT(soi.id) AS total_items,
                COALESCE(SUM(soi.qty),0) AS total_qty
            FROM sales_orders so
            LEFT JOIN sales_order_items soi ON soi.order_id = so.id
            GROUP BY so.id, so.order_no, so.customer_name, so.order_date, so.status
            ORDER BY so.id DESC
        ");
    }

    public static function findOrder($id)
    {
        $r = DB::select("SELECT * FROM sales_orders WHERE id = ? LIMIT 1", [$id]);
        return $r ? $r[0] : null;
    }

    public static function createOrder($orderNo, $orderDate, $customerName, $createdBy, $phone = null, $address = null, $note = null)
    {
        return DB::table('sales_orders')->insertGetId([
            'order_no' => $orderNo,
            'order_date' => $orderDate,
            'customer_name' => $customerName,
            'customer_phone' => $phone,
            'customer_address' => $address,
            'status' => 'Open',
            'note' => $note,
            'created_by' => $createdBy,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function getItems($orderId)
    {
        return DB::select("
            SELECT
                soi.id, soi.product_id, soi.qty,
                p.sku, p.name AS product_name
            FROM sales_order_items soi
            JOIN products p ON p.id = soi.product_id
            WHERE soi.order_id = ?
            ORDER BY soi.id DESC
        ", [$orderId]);
    }

    public static function addItem($orderId, $productId, $qty)
    {
        $exists = DB::select("
            SELECT id, qty FROM sales_order_items
            WHERE order_id = ? AND product_id = ? LIMIT 1
        ", [$orderId, $productId]);

        if ($exists) {
            DB::table('sales_order_items')->where('id', $exists[0]->id)->update([
                'qty' => (int)$exists[0]->qty + (int)$qty,
                'updated_at' => now(),
            ]);
            return $exists[0]->id;
        }

        return DB::table('sales_order_items')->insertGetId([
            'order_id' => $orderId,
            'product_id' => $productId,
            'qty' => $qty,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function deleteItem($itemId)
    {
        DB::table('sales_order_items')->where('id', $itemId)->delete();
    }

    // public static function deleteOrders(array $ids): array
    // {
    //     $usedIds = DB::table('outbounds')
    //         ->whereIn('order_id', $ids)
    //         ->pluck('order_id')
    //         ->toArray();

    //     $usedIds = array_values(array_unique($usedIds));
    //     $deletableIds = array_values(array_diff($ids, $usedIds));

    //     $usedOrders = [];
    //     if (count($usedIds) > 0) {
    //         $usedOrders = DB::table('sales_orders')
    //             ->whereIn('id', $usedIds)
    //             ->select('order_no')
    //             ->pluck('order_no')
    //             ->toArray();
    //     }

    //     if (count($deletableIds) === 0) {
    //         $txt = count($usedOrders) ? implode(', ', array_slice($usedOrders, 0, 10)) : '-';
    //         return [
    //             'type' => 'error',
    //             'message' => "Semua order yang dipilih tidak bisa dihapus karena sudah diproses outbound: {$txt}"
    //         ];
    //     }

    //     $deletedOrders = DB::table('sales_orders')
    //         ->whereIn('id', $deletableIds)
    //         ->select('order_no')
    //         ->pluck('order_no')
    //         ->toArray();

    //     DB::transaction(function () use ($deletableIds) {
    //         DB::table('sales_order_items')->whereIn('order_id', $deletableIds)->delete();
    //         DB::table('sales_orders')->whereIn('id', $deletableIds)->delete();
    //     });

    //     $limit = 10;
    //     $deletedPreview = implode(', ', array_slice($deletedOrders, 0, $limit));
    //     if (count($deletedOrders) > $limit) $deletedPreview .= '...';

    //     $msg = "Berhasil menghapus " . count($deletableIds) . " order: {$deletedPreview}.";

    //     if (count($usedIds) > 0) {
    //         $usedPreview = implode(', ', array_slice($usedOrders, 0, $limit));
    //         if (count($usedOrders) > $limit) $usedPreview .= '...';
    //         $msg .= " Tidak bisa menghapus " . count($usedIds) . " order (sudah outbound): {$usedPreview}.";
    //     }

    //     return [
    //         'type' => 'success',
    //         'message' => $msg
    //     ];
    // }

    public static function deleteOrders(array $ids): array
    {
        if (count($ids) === 0) {
            return [
                'type' => 'error',
                'message' => 'Tidak ada order dipilih'
            ];
        }

        $orderNos = DB::table('sales_orders')
            ->whereIn('id', $ids)
            ->pluck('order_no')
            ->toArray();

        DB::transaction(function () use ($ids) {
            DB::table('sales_order_items')->whereIn('order_id', $ids)->delete();
            DB::table('sales_orders')->whereIn('id', $ids)->delete();
        });

        $limit = 10;
        $preview = implode(', ', array_slice($orderNos, 0, $limit));
        if (count($orderNos) > $limit) $preview .= '...';

        return [
            'type' => 'success',
            'message' => 'Berhasil menghapus ' . count($ids) . ' order: ' . $preview
        ];
    }
}
