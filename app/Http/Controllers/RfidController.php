<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Rfid;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RfidController extends Controller
{
    public function index()
    {
        $rfid = Rfid::getRfidTags();
        return view('rfid.index', [
            'rfids' => $rfid,
            'title' => 'RFID Tags',
            'subtitle' => 'RFID Tag Management',
        ]);
    }

    public function create()
    {
        $product = Product::getProduct();
        return view('rfid.create', [
            'products' => $product,
            'title' => 'Add RFID Tag',
            'subtitle' => 'Create a new RFID tag',
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'epc' => 'required|string|max:128|unique:rfid_tags,epc',
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
            'status' => 'required|in:Active,Inactive,Damaged',
            'state'  => 'required|in:New,In_Stock,Out',
        ]);

        DB::table('rfid_tags')->insert([
            'epc' => $validated['epc'],
            'product_id' => $validated['product_id'],
            'qty' => $validated['qty'],
            'status' => $validated['status'],
            'state' => $validated['state'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('rfid-tags.index')->with('toast_success', 'RFID Tag berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rfid = DB::table('rfid_tags')->where('id', $id)->first();
        abort_if(!$rfid, 404);

        $locked = DB::table('inbound_items')->where('rfid_tag_id', $id)->exists()
            || DB::table('outbound_items')->where('rfid_tag_id', $id)->exists();

        if ($locked) {
            return redirect()->route('rfid-tags.index')
                ->with('error', 'RFID tag tidak bisa diedit karena sudah dipakai transaksi.');
        }

        $products = Product::getProduct();

        return view('rfid.create', [
            'rfid' => $rfid,
            'products' => $products,
            'title' => 'Edit RFID Tag',
            'subtitle' => 'Update RFID tag data',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'RFID Tag', 'url' => route('rfid-tags.index')],
                ['label' => 'Edit', 'url' => null],
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $tag = DB::table('rfid_tags')->where('id', $id)->first();
        abort_if(!$tag, 404);

        $validated = $request->validate([
            'epc' => 'required|string|max:128|unique:rfid_tags,epc,' . $id,
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['Active', 'Inactive', 'Damaged'])],
            'state'  => ['required', Rule::in(['New', 'In_Stock', 'Out'])],
        ]);

        DB::table('rfid_tags')->where('id', $id)->update([
            'epc' => $validated['epc'],
            'product_id' => $validated['product_id'],
            'qty' => $validated['qty'],
            'status' => $validated['status'],
            'state' => $validated['state'],
            'updated_at' => now(),
        ]);

        return redirect()->route('rfid-tags.index')->with('toast_success', 'RFID Tag berhasil diupdate.');
    }

    public function delete(Request $request)
    {
        $ids = $request->selected_tags;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Tidak ada data dipilih');
        }

        Rfid::whereIn('id', $ids)->delete();

        return redirect()->back()->with('toast_success', 'RFID Tag berhasil dihapus.');
    }
}
