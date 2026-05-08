<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::getUnit();

        return view('unit.index', [
            'title' => 'Units',
            'subtitle' => 'Unit Management',
            'units' => $units
        ]);
    }

    public function create()
    {
        return view('unit.create', [
            'title' => 'Create Unit',
            'subtitle' => 'Unit Management',
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:20',
        ]);

        Unit::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('unit.index')->with('toast_success', 'Unit berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $unit = Unit::where('id', $id)->first();

        return view('unit.create', [
            'unit' => $unit,
            'title' => 'Edit Unit',
            'subtitle' => 'Unit Management',
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'symbol' => 'nullable|string|max:20',
        ]);

        Unit::where('id', $id)->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'symbol' => $data['symbol'],
            'updated_at' => now(),
        ]);

        return redirect()->route('unit.index')->with('toast_success', 'Unit berhasil diperbarui.');
    }

    public function delete(Request $request)
    {
        $ids = $request->selected_units;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Tidak ada data dipilih');
        }

        Unit::whereIn('id', $ids)->delete();

        return redirect()->back()->with('toast_success', 'Unit berhasil dihapus.');
    }
}
