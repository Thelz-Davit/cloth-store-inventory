<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundleItem extends Model
{
    protected $fillable = [
        'bundle_id',
        'material_id',
        'quantity',
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}