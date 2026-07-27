<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = Bundle::with([
            'items.material' // Disesuaikan relasinya ke material
        ])->get();

        return view('bundlings.index', compact('bundles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();

        return view('bundlings.create', [
            'materials' => $materials,
            'isEdit' => false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bundle_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id', // Diubah ke material_id
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $bundling = Bundle::create([
                'bundle_name' => $validated['bundle_name'],
            ]);

            foreach ($validated['items'] as $item) {
                $bundling->items()->create([
                    'material_id' => $item['material_id'], // Diubah ke material_id
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('bundlings.index')
            ->with('toast_success', 'Bundle created successfully.');
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
        $bundling->load('items');
        $materials = Material::all();

        return view('bundlings.create', [
            'isEdit' => true,
            'bundle' => $bundling,
            'materials' => $materials,
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
            'items.*.material_id' => 'required|exists:materials,id', // Diubah ke material_id
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($bundling, $validated) {
            // Update bundle name
            $bundling->update([
                'bundle_name' => $validated['bundle_name'],
            ]);

            // Hapus item lama, masukkan item baru
            $bundling->items()->delete();

            foreach ($validated['items'] as $item) {
                $bundling->items()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('bundlings.index')
            ->with('toast_success', 'Bundle updated successfully.');
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