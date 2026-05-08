<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Outbound extends Model
{
    private static function placeholders($count)
    {
        return implode(',', array_fill(0, $count, '?'));
    }

    public static function getOpenOrders()
    {
        return DB::select("
            SELECT
                so.id,
                so.order_no,
                so.customer_name,
                so.order_date,
                so.status,
                COUNT(soi.id) AS total_items,
                COALESCE(SUM(soi.qty), 0) AS total_qty
            FROM sales_orders so
            LEFT JOIN sales_order_items soi ON soi.order_id = so.id
            WHERE so.status IN ('Open','Processing')
            GROUP BY so.id, so.order_no, so.customer_name, so.order_date, so.status
            ORDER BY so.id DESC
        ");
    }

    public static function getOrderHeader($orderId)
    {
        $r = DB::select("
            SELECT id, order_no, customer_name, order_date, status
            FROM sales_orders
            WHERE id = ?
            LIMIT 1
        ", [$orderId]);

        return $r ? $r[0] : null;
    }

    public static function getOrderItems($orderId)
    {
        return DB::select("
            SELECT
                soi.id,
                soi.product_id,
                soi.qty AS qty_order,
                p.sku,
                p.name AS product_name
            FROM sales_order_items soi
            JOIN products p ON p.id = soi.product_id
            WHERE soi.order_id = ?
            ORDER BY soi.id ASC
        ", [$orderId]);
    }

    public static function getDraft($orderId)
    {
        return session()->get("outbound_draft_$orderId", []); // [tag_id => qty]
    }

    public static function getTotals($orderId)
    {
        return session()->get("outbound_totals_$orderId", []); // [product_id => qty_scanned]
    }

    public static function getOrderItemsWithProgress($orderId)
    {
        $totals = self::getTotals($orderId);
        $items = self::getOrderItems($orderId);

        foreach ($items as $it) {
            $scanned = (int)($totals[$it->product_id] ?? 0);
            $it->qty_scanned = $scanned;
            $it->remaining = max(0, (int)$it->qty_order - $scanned);
        }

        return $items;
    }

    public static function getDraftTags($orderId)
    {
        $draft = self::getDraft($orderId);
        if (empty($draft)) return [];

        $ids = array_keys($draft);
        $ph  = self::placeholders(count($ids));

        $rows = DB::select("
            SELECT
                rt.id,
                rt.epc,
                rt.product_id,
                rt.qty AS qty_tag,
                rt.status,
                rt.state,
                p.sku,
                p.name AS product_name
            FROM rfid_tags rt
            JOIN products p ON p.id = rt.product_id
            WHERE rt.id IN ($ph)
            ORDER BY rt.id DESC
        ", $ids);

        foreach ($rows as $r) {
            $r->qty = (int)($draft[$r->id] ?? ($r->qty_tag ?? 1));
        }

        return $rows;
    }

    public static function canCommit($orderId)
    {
        $items = self::getOrderItemsWithProgress($orderId);
        if (empty($items)) return false;

        foreach ($items as $it) {
            if ((int)$it->remaining > 0) return false;
        }
        return true;
    }
}
