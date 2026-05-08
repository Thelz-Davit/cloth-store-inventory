<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutboundController extends Controller
{
    private function norm($v): string
    {
        $v = trim((string) $v);
        $v = str_replace(' ', '_', $v);
        return strtolower($v);
    }

    public function index()
    {
        $orders = Outbound::getOpenOrders();

        return view('outbound.index', [
            'title' => 'Outbound',
            'subtitle' => 'Outbound Management',
            'orders' => $orders,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Outbound', 'url' => null],
            ]
        ]);
    }

    public function process($orderId)
    {
        $order = Outbound::getOrderHeader($orderId);
        abort_if(!$order, 404);

        $items = Outbound::getOrderItemsWithProgress($orderId);
        $draftTags = Outbound::getDraftTags($orderId);
        $canCommit = Outbound::canCommit($orderId);

        return view('outbound.process', [
            'title' => 'Outbound Process',
            'subtitle' => 'Scan tag sesuai Sales Order',
            'order' => $order,
            'items' => $items,
            'draftTags' => $draftTags,
            'canCommit' => $canCommit,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Outbound', 'url' => route('outbound.index')],
                ['label' => 'Process', 'url' => null],
            ]
        ]);
    }

    public function scan(Request $request, $orderId)
    {
        $epc = trim((string) $request->input('epc'));
        if ($epc === '') {
            return back()->with('error', 'EPC wajib diisi');
        }

        $tag = DB::table('rfid_tags')->where('epc', $epc)->first();
        if (!$tag) {
            return back()->with('error', 'Tag tidak ditemukan');
        }

        $status = $this->norm($tag->status ?? '');
        $state  = $this->norm($tag->state ?? '');

        if ($status !== 'active') {
            return back()->with('error', 'Tag tidak aktif');
        }

        if ($state !== 'in_stock') {
            return back()->with('error', 'Tag belum In Stock / sudah Out');
        }

        $oi = DB::table('sales_order_items')
            ->where('order_id', $orderId)
            ->where('product_id', $tag->product_id)
            ->first();

        if (!$oi) {
            return back()->with('error', 'Tag ini tidak sesuai item pada Sales Order');
        }

        $draftKey = "outbound_draft_{$orderId}";
        $totalKey = "outbound_totals_{$orderId}";

        $draft  = session()->get($draftKey, []);
        $totals = session()->get($totalKey, []);

        if (isset($draft[$tag->id])) {
            return back()->with('error', 'Tag ini sudah di-scan di draft');
        }

        $qtyTag = (int)($tag->qty ?? 1);
        if ($qtyTag < 1) $qtyTag = 1;

        $scanned   = (int)($totals[$tag->product_id] ?? 0);
        $remaining = (int)$oi->qty - $scanned;

        if ($remaining <= 0) {
            return back()->with('error', 'Qty produk ini di order sudah terpenuhi');
        }

        $qtyUse = min($qtyTag, $remaining);

        $draft[$tag->id] = $qtyUse;
        $totals[$tag->product_id] = $scanned + $qtyUse;

        session()->put($draftKey, $draft);
        session()->put($totalKey, $totals);

        return back()->with('success', "Scan berhasil: {$epc} (ambil {$qtyUse} dari tag qty {$qtyTag})");
    }

    public function commit($orderId)
    {
        $draftKey = "outbound_draft_$orderId";
        $totalKey = "outbound_totals_$orderId";

        $draft = session()->get($draftKey, []);
        if (empty($draft)) return back()->with('error', 'Draft kosong');

        if (!Outbound::canCommit($orderId)) {
            return back()->with('error', 'Belum memenuhi qty order. Remaining masih ada.');
        }

        $now = Carbon::now('Asia/Jakarta');

        DB::transaction(function () use ($orderId, $draft, $now) {

            $outboundNo = 'OUT-' . $now->format('YmdHis');

            $outboundId = DB::table('outbounds')->insertGetId([
                'outbound_no' => $outboundNo,
                'order_id' => $orderId,
                'shipped_at' => $now,
                'user_id' => Auth::id(),
                'created_at' => $now,
            ]);

            $tagIds = array_keys($draft);

            $tags = DB::table('rfid_tags')
                ->whereIn('id', $tagIds)
                ->lockForUpdate()
                ->get();

            foreach ($tags as $tag) {
                $qty = (int)($draft[$tag->id] ?? 1);
                if ($qty < 1) $qty = 1;

                $balance = DB::table('stock_balances')
                    ->where('product_id', $tag->product_id)
                    ->lockForUpdate()
                    ->first();

                $current = (int)($balance->qty ?? 0);
                if ($current < $qty) {
                    throw new \Exception("Stok tidak cukup untuk product_id {$tag->product_id}");
                }

                DB::table('outbound_items')->insert([
                    'outbound_id' => $outboundId,
                    'rfid_tag_id' => $tag->id,
                    'product_id' => $tag->product_id,
                    'qty' => $qty,
                    'created_at' => $now,
                ]);

                DB::table('stock_balances')
                    ->where('product_id', $tag->product_id)
                    ->decrement('qty', $qty);

                DB::table('stock_movements')->insert([
                    'product_id' => $tag->product_id,
                    'qty_change' => -$qty,
                    'ref_type' => 'OUTBOUND',
                    'ref_id' => $outboundId,
                    'created_by' => Auth::id(),
                    'created_at' => $now,
                ]);

                $oldQty = (int)($tag->qty ?? 1);
                if ($oldQty < 1) $oldQty = 1;

                $newQty = $oldQty - $qty;

                if ($newQty <= 0) {
                    DB::table('rfid_tags')->where('id', $tag->id)->update([
                        'qty' => 0,
                        'state' => 'Out',
                        'updated_at' => $now,
                    ]);
                } else {
                    DB::table('rfid_tags')->where('id', $tag->id)->update([
                        'qty' => $newQty,
                        'state' => 'In_Stock',
                        'updated_at' => $now,
                    ]);
                }
            }

            DB::table('sales_orders')->where('id', $orderId)->update([
                'status' => 'Done',
                'updated_at' => $now,
            ]);
        });

        session()->forget($draftKey);
        session()->forget($totalKey);

        return redirect()->route('outbound.index')->with('success', 'Outbound berhasil disimpan');
    }

    public function history()
    {
        $rows = DB::select("
            SELECT
                o.id,
                o.outbound_no,
                o.shipped_at,
                so.order_no,
                so.customer_name,
                COALESCE(SUM(oi.qty),0) AS total_qty
            FROM outbounds o
            JOIN sales_orders so ON so.id = o.order_id
            LEFT JOIN outbound_items oi ON oi.outbound_id = o.id
            GROUP BY o.id, o.outbound_no, o.shipped_at, so.order_no, so.customer_name
            ORDER BY o.id DESC
        ");

        return view('outbound.history', [
            'title' => 'Outbound History',
            'subtitle' => 'Riwayat barang keluar',
            'rows' => $rows,
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Outbound', 'url' => route('outbound.index')],
                ['label' => 'History', 'url' => null],
            ]
        ]);
    }

    public function show($id)
    {
        $outbound = DB::select("
            SELECT
                o.id, o.outbound_no, o.shipped_at,
                so.order_no, so.customer_name
            FROM outbounds o
            JOIN sales_orders so ON so.id = o.order_id
            WHERE o.id = ?
            LIMIT 1
        ", [$id]);

        abort_if(!$outbound, 404);
        $outbound = $outbound[0];

        $items = DB::select("
            SELECT
                oi.id,
                oi.qty,
                rt.epc,
                p.sku,
                p.name AS product_name
            FROM outbound_items oi
            JOIN rfid_tags rt ON rt.id = oi.rfid_tag_id
            JOIN products p ON p.id = oi.product_id
            WHERE oi.outbound_id = ?
            ORDER BY oi.id DESC
        ", [$id]);

        return view('outbound.show', [
            'title' => 'Outbound Detail',
            'subtitle' => 'Detail item outbound',
            'outbound' => $outbound,
            'items' => $items,
        ]);
    }
}
