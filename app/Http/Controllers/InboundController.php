<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboundController extends Controller
{
    private function norm($v): string
    {
        $v = trim((string) $v);
        $v = str_replace(' ', '_', $v);
        return strtolower($v);
    }
    public function index()
    {
        $inbounds = Inbound::getInBound();

        return view('inbound.index', [
            'title' => 'Inbound',
            'subtitle' => 'Inbound Management',
            'inbounds' => $inbounds,
            'inbound_count' => is_array($inbounds) ? count($inbounds) : $inbounds->count(),
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Inbound', 'url' => null],
            ]
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate([
            'epc' => 'required|string|max:128',
        ]);

        $epc = trim($request->epc);

        $tag = DB::table('rfid_tags')
            ->where('epc', $epc)
            ->first();

        if (!$tag) {
            DB::table('scan_errors')->insert([
                'epc' => $epc,
                'reason' => 'RFID/QR tidak terdaftar',
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
            return back()->with('error', 'RFID/QR tidak terdaftar');
        }

        $status = $this->norm($tag->status);
        $state  = $this->norm($tag->state);

        if ($status !== 'active') {
            DB::table('scan_errors')->insert([
                'epc' => $epc,
                'reason' => 'Tag tidak aktif / rusak',
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
            return back()->with('error', 'Tag tidak valid (Inactive/Damaged)');
        }

        if ($state === 'in_stock') {
            DB::table('scan_errors')->insert([
                'epc' => $epc,
                'reason' => 'Tag sudah In_Stock (double inbound)',
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
            return back()->with('error', 'Tag sudah ada di gudang (In Stock)');
        }

        $draft = session()->get('inbound_draft', []);
        if (in_array($tag->id, $draft)) {
            return back()->with('error', 'Tag ini sudah ada di draft');
        }

        $draft[] = $tag->id;
        session()->put('inbound_draft', $draft);

        return back()->with('success', "Scan berhasil: {$epc}");
    }

    public function commit()
    {
        $tagIds = session()->get('inbound_draft', []);
        if (empty($tagIds)) return back()->with('error', 'Draft kosong');

        $now = Carbon::now('Asia/Jakarta');
        $inboundNo = 'INB-' . $now->format('YmdHis');

        DB::transaction(function () use ($tagIds, $now, $inboundNo) {

            $inboundId = DB::table('inbounds')->insertGetId([
                'inbound_no' => $inboundNo,
                'received_at' => $now,
                'user_id' => Auth::id(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $tags = DB::table('rfid_tags')
                ->whereIn('id', $tagIds)
                ->lockForUpdate()
                ->get();

            foreach ($tags as $tag) {
                $status = $this->norm($tag->status);
                $state  = $this->norm($tag->state);

                if ($status !== 'active') continue;
                if ($state === 'in_stock') continue;

                $qty = (int) ($tag->qty ?? 1);
                if ($qty < 1) $qty = 1;

                DB::table('inbound_items')->insert([
                    'inbound_id' => $inboundId,
                    'rfid_tag_id' => $tag->id,
                    'product_id' => $tag->product_id,
                    'qty' => $qty,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $exists = DB::table('stock_balances')->where('product_id', $tag->product_id)->exists();
                if (!$exists) {
                    DB::table('stock_balances')->insert([
                        'product_id' => $tag->product_id,
                        'qty' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table('stock_balances')->where('product_id', $tag->product_id)->increment('qty', $qty);

                DB::table('stock_movements')->insert([
                    'product_id' => $tag->product_id,
                    'qty_change' => $qty,
                    'ref_type' => 'INBOUND',
                    'ref_id' => $inboundId,
                    'created_by' => Auth::id(),
                    'created_at' => $now,
                ]);

                DB::table('rfid_tags')->where('id', $tag->id)->update([
                    'state' => 'In_Stock',
                    'updated_at' => $now,
                ]);
            }
        });

        session()->forget('inbound_draft');
        return back()->with('success', 'Inbound berhasil disimpan');
    }

    public function removeDraft(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $draft = session()->get('inbound_draft', []);

        $draft = array_values(array_diff($draft, [(int)$request->id]));
        session()->put('inbound_draft', $draft);

        return back()->with('success', 'Item draft dihapus');
    }

    public function resetDraft()
    {
        session()->forget('inbound_draft');
        return back()->with('success', 'Draft direset');
    }
}
