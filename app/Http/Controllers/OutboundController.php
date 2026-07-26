<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Outbound;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OutboundController extends Controller
{

    public function index()
    {
        $outbounds = Outbound::with([
            'bundle.products.material',
            'bundle.products.color',
            'bundle.products.size',
        ])->get();

        return view('outbounds.index', compact('outbounds'));
    }


    public function create()
    {
        $bundles = Bundle::all();
        $outbounds = Outbound::all();
        return view('outbounds.create', compact('bundles', 'outbounds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outbound_date' => 'required|date',
            'bundle_id' => 'required|exists:bundles,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // 2. Save as draft?
        if ($request->save_as_draft) {

            Outbound::create([
                'outbound_date' => $validated['outbound_date'],
                'bundle_id' => $validated['bundle_id'],
                'quantity' => $validated['quantity'],
                'user_id' => auth()->id(),
                'status' => 'draft',
            ]);

            return redirect()
                ->route('outbounds.index')
                ->with('toast_success', 'Draft saved successfully.');
        }

        $bundle = Bundle::with([
            'products.material',
            'products.color',
            'products.size',
        ])->findOrFail($validated['bundle_id']);

        $failedProducts = [];

        // Check stock
        foreach ($bundle->products as $product) {

            $required = $product->pivot->quantity * $validated['quantity'];

            if ($product->stock < $required) {

                $failedProducts[] = [
                    'product_name' => $product->product_name,
                    'material' => optional($product->material)->material_name,
                    'color' => optional($product->color)->color_name,
                    'size' => optional($product->size)->size_name,
                    'required' => $required,
                    'available' => $product->stock,
                ];
            }
        }

        // Stop if any product is insufficient
        if (!empty($failedProducts)) {
            return back()
                ->withInput()
                ->with('failedProducts', $failedProducts)
                ->with('showDraft', true);
        }

        DB::transaction(function () use ($bundle, $validated) {

            foreach ($bundle->products as $product) {

                $required = $product->pivot->quantity * $validated['quantity'];

                $product->decrement('stock', $required);
            }

            Outbound::create([
                'outbound_date' => $validated['outbound_date'],
                'bundle_id' => $validated['bundle_id'],
                'quantity' => $validated['quantity'],
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('outbounds.index')
            ->with('toast_success', 'Outbound recorded successfully.');
    }

    public function edit(Outbound $outbound)
    {
        $bundles = Bundle::with([
            'products.material',
            'products.color',
            'products.size'
        ])->get();

        return view('outbounds.create', [
            'isEdit' => true,
            'outbound' => $outbound,
            'bundles' => $bundles,
        ]);
    }

    public function update(Request $request, Outbound $outbound)
    {
        $validated = $request->validate([
            'outbound_date' => 'required|date',
            'bundle_id' => 'required|exists:bundles,id',
            'quantity' => 'required|integer|min:1',
        ]);


        $oldBundle = Bundle::with('products')->findOrFail($outbound->bundle_id);
        $newBundle = Bundle::with([
            'products.material',
            'products.color',
            'products.size'
        ])->findOrFail($validated['bundle_id']);

        // DB::transaction(function () use ($oldBundle, $newBundle, $outbound, $validated) {

        //     // Restore stock from old outbound
        //     foreach ($oldBundle->products as $product) {

        //         $restore = $product->pivot->quantity * $outbound->quantity;

        //         $product->increment('stock', $restore);
        //     }

        //     // Check stock for new outbound
        //     $failedProducts = [];

        //     foreach ($newBundle->products as $product) {

        //         $required = $product->pivot->quantity * $validated['quantity'];

        //         if ($product->stock < $required) {

        //             $failedProducts[] = [
        //                 'product_name' => $product->product_name,
        //                 'material' => optional($product->material)->material_name,
        //                 'color' => optional($product->color)->color_name,
        //                 'size' => optional($product->size)->size_name,
        //                 'required' => $required,
        //                 'available' => $product->stock,
        //             ];
        //         }
        //     }

        //     if (!empty($failedProducts)) {

        //         // Put stock back to original state
        //         foreach ($oldBundle->products as $product) {

        //             $restore = $product->pivot->quantity * $outbound->quantity;

        //             $product->decrement('stock', $restore);
        //         }

        //         throw \Illuminate\Validation\ValidationException::withMessages([
        //             'stock' => 'One or more products do not have enough stock.'
        //         ])->errorBag('default');
        //     }

        //     // Deduct new stock
        //     foreach ($newBundle->products as $product) {

        //         $required = $product->pivot->quantity * $validated['quantity'];

        //         $product->decrement('stock', $required);
        //     }

        //     // Update outbound
        //     $outbound->update([
        //         'outbound_date' => $validated['outbound_date'],
        //         'bundle_id' => $validated['bundle_id'],
        //         'quantity' => $validated['quantity'],
        //     ]);

        // });
        DB::transaction(function () use ($oldBundle, $newBundle, $outbound, $validated) {

            // Only restore stock if this outbound was already completed
            if ($outbound->status === 'Completed') {
                foreach ($oldBundle->products as $product) {
                    $restore = $product->pivot->quantity * $outbound->quantity;
                    $product->increment('stock', $restore);
                }
            }

            // Check stock
            $failedProducts = [];

            foreach ($newBundle->products as $product) {

                $required = $product->pivot->quantity * $validated['quantity'];

                if ($product->stock < $required) {
                    $failedProducts[] = $product->product_name;
                }
            }

            if (!empty($failedProducts)) {

                // Undo restored stock only if it was restored
                if ($outbound->status === 'Completed') {
                    foreach ($oldBundle->products as $product) {
                        $restore = $product->pivot->quantity * $outbound->quantity;
                        $product->decrement('stock', $restore);
                    }
                }

                throw ValidationException::withMessages([
                    'stock' => 'One or more products do not have enough stock.',
                ]);
            }

            // Deduct stock
            foreach ($newBundle->products as $product) {
                $required = $product->pivot->quantity * $validated['quantity'];
                $product->decrement('stock', $required);
            }

            // If it was Draft, complete it
            $outbound->update([
                'outbound_date' => $validated['outbound_date'],
                'bundle_id' => $validated['bundle_id'],
                'quantity' => $validated['quantity'],
                'status' => 'completed',
            ]);
        });

        return redirect()
            ->route('outbounds.index')
            ->with('toast_success', 'Outbound updated successfully.');
    }

    public function destroy(Outbound $outbound)
    {
        DB::transaction(function () use ($outbound) {

            $bundle = Bundle::with('products')->findOrFail($outbound->bundle_id);

            foreach ($bundle->products as $product) {

                $restore = $product->pivot->quantity * $outbound->quantity;

                $product->increment('stock', $restore);
            }

            $outbound->delete();
        });

        return redirect()
            ->route('outbounds.index')
            ->with('toast_success', 'Outbound deleted successfully.');
    }

    public function storeDraft(Request $request)
    {
        $validated = $request->validate([
            'outbound_date' => 'required|date',
            'bundle_id' => 'required|exists:bundles,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';

        Outbound::create($validated);

        return redirect()
            ->route('outbounds.index')
            ->with('toast_success', 'Draft saved successfully.');
    }

    // public function commit($orderId)
    // {
    //     $draftKey = "outbound_draft_$orderId";
    //     $totalKey = "outbound_totals_$orderId";

    //     $draft = session()->get($draftKey, []);
    //     if (empty($draft)) return back()->with('error', 'Draft kosong');

    //     if (!Outbound::canCommit($orderId)) {
    //         return back()->with('error', 'Belum memenuhi qty order. Remaining masih ada.');
    //     }

    //     $now = Carbon::now('Asia/Jakarta');

    //     DB::transaction(function () use ($orderId, $draft, $now) {

    //         $outboundNo = 'OUT-' . $now->format('YmdHis');

    //         $outboundId = DB::table('outbounds')->insertGetId([
    //             'outbound_no' => $outboundNo,
    //             'order_id' => $orderId,
    //             'shipped_at' => $now,
    //             'user_id' => Auth::id(),
    //             'created_at' => $now,
    //         ]);

    //         $tagIds = array_keys($draft);

    //         $tags = DB::table('rfid_tags')
    //             ->whereIn('id', $tagIds)
    //             ->lockForUpdate()
    //             ->get();

    //         foreach ($tags as $tag) {
    //             $qty = (int)($draft[$tag->id] ?? 1);
    //             if ($qty < 1) $qty = 1;

    //             $balance = DB::table('stock_balances')
    //                 ->where('product_id', $tag->product_id)
    //                 ->lockForUpdate()
    //                 ->first();

    //             $current = (int)($balance->qty ?? 0);
    //             if ($current < $qty) {
    //                 throw new \Exception("Stok tidak cukup untuk product_id {$tag->product_id}");
    //             }

    //             DB::table('outbound_items')->insert([
    //                 'outbound_id' => $outboundId,
    //                 'rfid_tag_id' => $tag->id,
    //                 'product_id' => $tag->product_id,
    //                 'qty' => $qty,
    //                 'created_at' => $now,
    //             ]);

    //             DB::table('stock_balances')
    //                 ->where('product_id', $tag->product_id)
    //                 ->decrement('qty', $qty);

    //             DB::table('stock_movements')->insert([
    //                 'product_id' => $tag->product_id,
    //                 'qty_change' => -$qty,
    //                 'ref_type' => 'OUTBOUND',
    //                 'ref_id' => $outboundId,
    //                 'created_by' => Auth::id(),
    //                 'created_at' => $now,
    //             ]);

    //             $oldQty = (int)($tag->qty ?? 1);
    //             if ($oldQty < 1) $oldQty = 1;

    //             $newQty = $oldQty - $qty;

    //             if ($newQty <= 0) {
    //                 DB::table('rfid_tags')->where('id', $tag->id)->update([
    //                     'qty' => 0,
    //                     'state' => 'Out',
    //                     'updated_at' => $now,
    //                 ]);
    //             } else {
    //                 DB::table('rfid_tags')->where('id', $tag->id)->update([
    //                     'qty' => $newQty,
    //                     'state' => 'In_Stock',
    //                     'updated_at' => $now,
    //                 ]);
    //             }
    //         }

    //         DB::table('sales_orders')->where('id', $orderId)->update([
    //             'status' => 'Done',
    //             'updated_at' => $now,
    //         ]);
    //     });

    //     session()->forget($draftKey);
    //     session()->forget($totalKey);

    //     return redirect()->route('outbound.index')->with('success', 'Outbound berhasil disimpan');
    // }

}
