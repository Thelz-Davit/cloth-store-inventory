<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan model Product dipanggil

class InventoryController extends Controller
{
    public function index()
    {
        // Ambil semua data produk beserta relasinya (biar gak N+1 query issue)
        $products = Product::with(['material', 'color', 'size'])->get();

        // Arahkan ke file view resources/views/inventories/index.blade.php
        return view('inventories.index', compact('products'));
    }
}