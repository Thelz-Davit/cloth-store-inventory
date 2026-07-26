<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = Bundle::with([
            'items.product'
        ])->get();

        return view('bundlings.index', compact('bundles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', true)->get();

        return view('bundlings.create', [
            'products' => $products,
            'isEdit' => false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bundle_name' => 'required',

            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',

            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Create bundle
        $bundling = Bundle::create([
            'bundle_name' => $validated['bundle_name'],
        ]);

        foreach ($validated['items'] as $item) {
            $bundling->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()
            ->route('bundlings.index')
            ->with('success', 'Bundle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bundle $bundling)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bundle $bundling)
    {
        $bundling->load('products');

        $products = Product::all();

        return view('bundlings.create', [
            'isEdit' => true,
            'bundle' => $bundling,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Bundle $bundling)
    {
        $validated = $request->validate([
            'bundle_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id|distinct',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($bundling, $validated) {

            // Update bundle name
            $bundling->update([
                'bundle_name' => $validated['bundle_name'],
            ]);

            // Prepare sync data
            $sync = [];

            foreach ($validated['items'] as $item) {
                $sync[$item['product_id']] = [
                    'quantity' => $item['quantity'],
                ];
            }

            // Update pivot table
            $bundling->products()->sync($sync);
        });

        return redirect()
            ->route('bundlings.index')
            ->with('success', 'Bundle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bundle $bundling)
    {
        // Delete all bundle items
        $bundling->items()->delete();

        // Delete the bundle
        $bundling->delete();

        return redirect()
            ->route('bundlings.index')
            ->with('toast_success', 'Bundle deleted successfully.');
    }
}
