<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Outbound;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OutboundController extends Controller
{
    public function index()
    {
        $outbounds = Outbound::with(['bundle', 'user'])->get();
        return view('outbounds.index', compact('outbounds'));
    }

    public function create()
    {
        $bundles = Bundle::all();
        $outbounds = Outbound::all();
        return view('outbounds.create', compact('bundles', 'outbounds'));
    }

    // API Endpoint untuk AJAX di View create.blade.php
    public function getBundleDetails($id)
    {
        $bundle = Bundle::with([
            'items.material.products' => function($query) {
                $query->where('status', 1)        // Hanya ambil produk yang statusnya aktif
                      ->where('stock', '>', 0)    // Hanya ambil produk yang stoknya ada
                      ->with(['color', 'size']);
            }
        ])->findOrFail($id);

        return response()->json($bundle);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outbound_date'     => 'required|date',
            'bundle_id'         => 'required|exists:bundles,id',
            'quantity'          => 'required|integer|min:1',
            'selected_products' => 'required|array', // Array ID Produk Fisik hasil pilihan staf di Form
            'selected_products.*' => 'exists:products,id',
        ]);

        // 1. Simpan Draft jika tombol Save Draft diklik
        if ($request->save_as_draft) {
            Outbound::create([
                'outbound_date' => $validated['outbound_date'],
                'bundle_id'     => $validated['bundle_id'],
                'quantity'      => $validated['quantity'],
                'user_id'       => auth()->id(),
                'status'        => 'draft',
            ]);

            return redirect()
                ->route('outbounds.index')
                ->with('toast_success', 'Draft saved successfully.');
        }

        $bundle = Bundle::with('items')->findOrFail($validated['bundle_id']);
        $failedProducts = [];
        $targetProducts = [];

        // 2. Pengecekan Stok Fisik berdasarkan ID Produk yang dipilih
        foreach ($validated['selected_products'] as $productId) {
            $product = Product::with(['material', 'color', 'size'])->findOrFail($productId);
            
            // Cari rasio resep material dari bundle_items
            $bundleItem = $bundle->items->where('material_id', $product->material_id)->first();
            $recipeQty  = $bundleItem ? $bundleItem->quantity : 1;

            $required = $recipeQty * $validated['quantity'];

            if ($product->stock < $required) {
                $failedProducts[] = [
                    'product_name' => $product->product_name,
                    'material'     => optional($product->material)->material_name,
                    'color'        => optional($product->color)->color_name,
                    'size'         => optional($product->size)->size_name,
                    'required'     => $required,
                    'available'    => $product->stock,
                ];
            }

            $targetProducts[] = [
                'model'    => $product,
                'required' => $required,
            ];
        }

        // 3. Stop jika ada stok yang kurang (Tampilkan daftar barang yang kurang)
        if (!empty($failedProducts)) {
            return back()
                ->withInput()
                ->with('failedProducts', $failedProducts)
                ->with('showDraft', true);
        }

        // 4. Eksekusi Potong Stok dalam DB Transaction
        DB::transaction(function () use ($targetProducts, $validated) {
            foreach ($targetProducts as $item) {
                $item['model']->decrement('stock', $item['required']);
            }

            Outbound::create([
                'outbound_date' => $validated['outbound_date'],
                'bundle_id'     => $validated['bundle_id'],
                'quantity'      => $validated['quantity'],
                'user_id'       => auth()->id(),
                'status'        => 'completed',
            ]);
        });

        return redirect()
            ->route('outbounds.index')
            ->with('toast_success', 'Outbound recorded successfully.');
    }

    public function edit(Outbound $outbound)
    {
        $bundles = Bundle::all();

        return view('outbounds.create', [
            'isEdit'   => true,
            'outbound' => $outbound,
            'bundles'  => $bundles,
        ]);
    }

    public function destroy(Outbound $outbound)
    {
        DB::transaction(function () use ($outbound) {
            // Jika statusnya completed, kembalikan stok produk berdasarkan bundle dan materialnya
            if ($outbound->status === 'completed') {
                $bundle = Bundle::with('items')->find($outbound->bundle_id);

                if ($bundle) {
                    foreach ($bundle->items as $item) {
                        // Karena di bundle_items kolomnya adalah material_id:
                        $materialId = $item->material_id ?? null;

                        if ($materialId) {
                            // Cari produk-produk fisik yang memiliki material_id tersebut
                            $products = Product::where('material_id', $materialId)->get();

                            foreach ($products as $product) {
                                // Hitung jumlah pengembalian (qty resep * qty outbound)
                                $restoreQty = $item->quantity * $outbound->quantity;
                                $product->increment('stock', $restoreQty);
                            }
                        }
                    }
                }
            }

            // Hapus data outbound setelah stok dikembalikan
            $outbound->delete();
        });

        return redirect()
            ->route('outbounds.index')
            ->with('toast_success', 'Outbound deleted and stock restored successfully.');
    }
}