<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        return view('master-data.index'); // Sesuaikan jika halaman index materinya gabung di sini
    }

    // TAMBAHKAN FUNGSI INI SUPAYA TOMBOL "ADD NEW MATERIAL" BERFUNGSI
    public function create()
    {
        return view('master-data.material-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
        ]);

        Material::create($request->all());

        return redirect()->route('master-data.index')->with('toast_success', 'Material berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('master-data.material-edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'material_name' => 'required|string|max:255',
        ]);

        $material = Material::findOrFail($id);
        $material->update($request->all());

        return redirect()->route('master-data.index')->with('toast_success', 'Material berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('master-data.index')->with('toast_success', 'Material berhasil dihapus!');
    }
}