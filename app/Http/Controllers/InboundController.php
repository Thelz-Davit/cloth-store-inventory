<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboundController extends Controller
{

    public function index()
    {
        $inbounds = Inbound::with([
            'product.material',
            'product.color',
            'product.size',
        ])->get();

        return view('inbounds.index', compact('inbounds'));
    }

    public function create()
    {
        $products = Product::all();
        return view('inbounds.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inbound_date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $validated['user_id'] = auth()->id();
            Inbound::create($validated);

            // Update matching product stock
            $product = Product::findOrFail($validated['product_id']);
            $product->increment('stock', $validated['quantity']);
        });

        return redirect()
            ->route('inbounds.index')
            ->with('toast_success', 'Inbound recorded successfully.');
    }

    public function edit(Inbound $inbound)
    {
        $products = Product::with([
            'material',
            'color',
            'size',
        ])->get();

        return view('inbounds.create', [
            'isEdit' => true,
            'inbound' => $inbound,
            'products' => $products,
        ]);
    }

    public function update(Request $request, Inbound $inbound)
    {
        $validated = $request->validate([
            'inbound_date' => 'required|date',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $inbound) {

            // Restore stock from the old inbound
            $oldProduct = Product::findOrFail($inbound->product_id);
            $oldProduct->decrement('stock', $inbound->quantity);

            // Apply stock to the new product
            $newProduct = Product::findOrFail($validated['product_id']);
            $newProduct->increment('stock', $validated['quantity']);

            // Update inbound record
            $inbound->update([
                'inbound_date' => $validated['inbound_date'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        });

        return redirect()
            ->route('inbounds.index')
            ->with('toast_success', 'Inbound updated successfully.');
    }

    public function destroy(Inbound $inbound)
    {
        DB::transaction(function () use ($inbound) {
            // Kurangi kembali stok produk karena inbound dihapus/dibatalkan
            $product = Product::findOrFail($inbound->product_id);
            $product->decrement('stock', $inbound->quantity);

            // Hapus data inbound
            $inbound->delete();
        });

        return redirect()
            ->route('inbounds.index')
            ->with('toast_success', 'Inbound deleted and stock adjusted successfully.');
    }
}