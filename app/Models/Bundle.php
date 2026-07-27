<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'bundle_name',
    ];

    // Relasi ke tabel resep bundle_items
    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    // Menghubungkan Bundle langsung ke Material lewat bundle_items
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'bundle_items', 'bundle_id', 'material_id')
                    ->withPivot('quantity');
    }
}