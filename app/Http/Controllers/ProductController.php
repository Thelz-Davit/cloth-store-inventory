<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function product()
    {
        $products = Product::getProduct();
        return view('product.index', [
            'title' => 'Inventory',
            'subtitle' => 'Inventory Management',
            'products' => $products
        ]);
    }

    public function create()
    {
        $units = Unit::getUnit();

        return view('product.create', [
            'title' => 'Create Product',
            'subtitle' => 'Inventory Management',
            'units' => $units
        ]);
    }

    public function history()
    {
        $products = Product::stockHistory();
        return view('product.history', [
            'title' => 'Inventory History',
            'subtitle' => 'Inventory Management',
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:products,sku',
            'name' => 'required|string|max:150',
            'unit_code' => 'required|string|max:10|exists:units,code',
        ]);

        DB::table('products')->insert([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'unit_code' => $validated['unit_code'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('product.index')
            ->with('toast_success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        $units = Unit::getUnit();

        return view('product.create', [
            'title' => 'Edit Product',
            'subtitle' => 'Product Management',
            'product' => $product,
            'units' => $units
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'name' => 'required|string|max:150',
            'unit_code' => 'required|string|max:10|exists:units,code',
        ]);

        DB::table('products')->where('id', $id)->update([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'unit_code' => $validated['unit_code'],
            'updated_at' => now(),
        ]);

        return redirect()->route('product.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    // public function delete(Request $request)
    // {
    //     $ids = $request->selected_products;

    //     if (!$ids || count($ids) === 0) {
    //         return back()->with('error', 'Tidak ada data dipilih');
    //     }

    //     $usedInTags   = DB::table('rfid_tags')->whereIn('product_id', $ids)->pluck('product_id')->toArray();
    //     $usedInInbound = DB::table('inbound_items')->whereIn('product_id', $ids)->pluck('product_id')->toArray();
    //     $usedInStock  = DB::table('stock_balances')->whereIn('product_id', $ids)->pluck('product_id')->toArray();

    //     $usedIds = array_values(array_unique(array_merge($usedInTags, $usedInInbound, $usedInStock)));
    //     $deletableIds = array_values(array_diff($ids, $usedIds));

    //     $usedNames = [];
    //     if (count($usedIds) > 0) {
    //         $usedNames = Product::whereIn('id', $usedIds)->pluck('name')->toArray();
    //     }

    //     if (count($deletableIds) === 0) {
    //         $namesText = count($usedNames) ? implode(', ', $usedNames) : '-';
    //         return back()->with(
    //             'toast_error',
    //             "Semua produk yang dipilih tidak bisa dihapus karena sudah dipakai: {$namesText}"
    //         );
    //     }

    //     $deletedNames = Product::whereIn('id', $deletableIds)->pluck('name')->toArray();

    //     Product::whereIn('id', $deletableIds)->delete();

    //     $limit = 10;

    //     $deletedPreview = implode(', ', array_slice($deletedNames, 0, $limit));
    //     if (count($deletedNames) > $limit) $deletedPreview .= '...';

    //     $msg = "Berhasil menghapus " . count($deletableIds) . " produk: {$deletedPreview}.";

    //     if (count($usedIds) > 0) {
    //         $usedPreview = implode(', ', array_slice($usedNames, 0, $limit));
    //         if (count($usedNames) > $limit) $usedPreview .= '...';

    //         $msg .= " Tidak bisa menghapus " . count($usedIds) . " produk (sudah dipakai): {$usedPreview}.";
    //     }

    //     return back()->with('toast_success', $msg);
    // }
    public function delete(Request $request)
    {
        $ids = $request->selected_products;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Tidak ada data dipilih');
        }

        $deletedNames = Product::whereIn('id', $ids)->pluck('name')->toArray();

        DB::transaction(function () use ($ids) {

            DB::table('stock_balances')->whereIn('product_id', $ids)->delete();
            DB::table('rfid_tags')->whereIn('product_id', $ids)->delete();
            DB::table('inbound_items')->whereIn('product_id', $ids)->delete();
            DB::table('outbound_items')->whereIn('product_id', $ids)->delete();
            DB::table('stock_movements')->whereIn('product_id', $ids)->delete();

            Product::whereIn('id', $ids)->delete();
        });

        $limit = 10;
        $preview = implode(', ', array_slice($deletedNames, 0, $limit));
        if (count($deletedNames) > $limit) $preview .= '...';

        return back()->with(
            'toast_success',
            'Berhasil menghapus ' . count($ids) . ' produk: ' . $preview
        );
    }
}
