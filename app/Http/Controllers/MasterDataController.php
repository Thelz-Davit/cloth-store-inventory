<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Color;
use App\Models\Size;

class MasterDataController extends Controller
{
    public function index()
    {
        $materials = Material::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('master-data.index', compact(
            'materials',
            'colors',
            'sizes'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Material
    |--------------------------------------------------------------------------
    */

    public function createMaterial()
    {
        return view('master-data.material-create');
    }

    public function storeMaterial(Request $request)
    {
        $validated = $request->validate([
            'material_name' => 'required|max:255',
        ]);

        Material::create($validated);

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Material created successfully.');
    }

    public function editMaterial(Material $material)
    {
        return view('master-data.material-create', compact('material'));
    }

    public function updateMaterial(Request $request, Material $material)
    {
        $validated = $request->validate([
            'material_name' => 'required|max:255',
        ]);

        $material->update($validated);

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Material updated successfully.');
    }

    public function destroyMaterial(Material $material)
    {
        if ($material->products()->exists()) {
            return redirect()
                ->route('master-data.index')
                ->with('toast_error', 'This material cannot be deleted because it is being used by one or more products.');
        }
        $material->delete();

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Material deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Color
    |--------------------------------------------------------------------------
    */

    public function createColor()
    {
        return view('master-data.color-create');
    }

    public function storeColor(Request $request)
    {
        $validated = $request->validate([
            'color_name' => 'required|max:255',
        ]);

        Color::create($validated);

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Color created successfully.');
    }

    public function editColor(Color $color)
    {
        return view('master-data.color-create', compact('color'));
    }

    public function updateColor(Request $request, Color $color)
    {
        $validated = $request->validate([
            'color_name' => 'required|max:255',
        ]);

        $color->update($validated);

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Color updated successfully.');
    }

    public function destroyColor(Color $color)
    {
        if ($color->products()->exists()) {
            return redirect()
                ->route('master-data.index')
                ->with('toast_error', 'This color cannot be deleted because it is being used by one or more products.');
        }
        $color->delete();

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Color deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Size
    |--------------------------------------------------------------------------
    */

    public function createSize()
    {
        return view('master-data.size-create');
    }

    public function storeSize(Request $request)
    {
        $validated = $request->validate([
            'size_name' => 'required|max:255',
        ]);

        Size::create($validated);

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Size created successfully.');
    }

    public function editSize(Size $size)
    {
        return view('master-data.size-create', compact('size'));
    }

    public function updateSize(Request $request, Size $size)
    {
        $validated = $request->validate([
            'size_name' => 'required|max:255',
        ]);

        $size->update($validated);

        return redirect()->route('master-data.index')
            ->with('toast_success', 'Size updated successfully.');
    }

    public function destroySize(Size $size)
    {
        if ($size->products()->exists()) {
            return redirect()
                ->route('master-data.index')
                ->with('toast_error', 'This size cannot be deleted because it is being used by one or more products.');
        }

        $size->delete();

        return redirect()
            ->route('master-data.index')
            ->with('toast_success', 'Size deleted successfully.');
    }
}
