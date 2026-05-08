<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // dd(auth()->user()->fresh());
        $now   = Carbon::now('Asia/Jakarta');
        $start = $now->copy()->subDays(13)->startOfDay();
        $end   = $now->copy()->endOfDay();

        $totalProducts = (int) DB::table('products')->count();

        $totalStock = (int) (DB::table('stock_balances')->sum('qty') ?? 0);

        $openOrders = (int) DB::table('sales_orders')
            ->where('status', '!=', 'Done')
            ->count();

        $tagsInStock = (int) DB::table('rfid_tags')
            ->whereIn('state', ['In_Stock', 'In Stock'])
            ->whereIn('status', ['Active', 'ACTIVE'])
            ->count();

        $movementRows = DB::select("
            SELECT DATE(created_at) AS d,
                SUM(CASE WHEN ref_type = 'INBOUND' THEN qty_change ELSE 0 END) AS in_qty,
                SUM(CASE WHEN ref_type = 'OUTBOUND' THEN -qty_change ELSE 0 END) AS out_qty
            FROM stock_movements
            WHERE created_at BETWEEN ? AND ?
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
        ", [$start, $end]);

        $labels = [];
        $inData = [];
        $outData = [];

        $map = [];
        foreach ($movementRows as $r) {
            $map[$r->d] = [
                'in'  => (int) ($r->in_qty ?? 0),
                'out' => (int) ($r->out_qty ?? 0),
            ];
        }

        for ($i = 13; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i)->format('Y-m-d');
            $labels[] = $d;
            $inData[] = $map[$d]['in'] ?? 0;
            $outData[] = $map[$d]['out'] ?? 0;
        }

        $topStockRows = DB::select("
            SELECT p.sku, p.name, COALESCE(sb.qty, 0) AS qty
            FROM products p
            LEFT JOIN stock_balances sb ON sb.product_id = p.id
            ORDER BY qty DESC, p.id DESC
            LIMIT 10
        ");

        $topLabels = [];
        $topQty = [];
        foreach ($topStockRows as $r) {
            $topLabels[] = $r->sku . ' - ' . $r->name;
            $topQty[] = (int) $r->qty;
        }

        $statusRows = DB::select("
            SELECT COALESCE(status,'Open') AS status, COUNT(*) AS total
            FROM sales_orders
            GROUP BY COALESCE(status,'Open')
            ORDER BY total DESC
        ");

        $statusLabels = [];
        $statusTotals = [];
        foreach ($statusRows as $r) {
            $statusLabels[] = $r->status;
            $statusTotals[] = (int) $r->total;
        }

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'subtitle' => 'Ringkasan laporan Inventory & Operasional',

            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'openOrders' => $openOrders,
            'tagsInStock' => $tagsInStock,

            'mvLabels' => $labels,
            'mvInbound' => $inData,
            'mvOutbound' => $outData,

            'topLabels' => $topLabels,
            'topQty' => $topQty,

            'statusLabels' => $statusLabels,
            'statusTotals' => $statusTotals,
        ]);
    }
}
