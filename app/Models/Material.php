<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_name',
    ];

    // Relasi ke tabel products (untuk cek stok fisik berdasarkan warna/size)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // TAMBAHKAN INI: Relasi ke tabel bundle_items (resep paket)
    public function bundleItems()
    {
        return $this->hasMany(BundleItem::class, 'material_id');
    }
}