<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'material',
            'color',
            'size',
        ])->get();

        return view('products.index', compact('products'));
    }

    public function indexInventory()
    {
        $products = Product::with([
            'material',
            'color',
            'size',
        ])->get();

        return view('inventories.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();
        return view('products.create', compact('sizes', 'colors','materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|max:255',
            'material_id' => 'nullable|exists:materials,id',
            'color_id'    => 'nullable|exists:colors,id',
            'size_id'     => 'nullable|exists:sizes,id',
        ]);

        $validated['status'] = true;
        $validated['stock'] = 0;
        Product::create($validated);

        return redirect()->route('products.index')->with('toast_success', 'Product Added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();
        return view('products.create', compact('product','colors','sizes','materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
        'product_name' => 'required|max:255',
        'material_id'  => 'nullable|exists:materials,id',
        'color_id'     => 'nullable|exists:colors,id',
        'size_id'      => 'nullable|exists:sizes,id',
        'status' => 'required|boolean',
    ]);

    $product->update($validated);

    return redirect()->route('products.index')->with('toast_success', 'Product updated Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index');
    }

}
