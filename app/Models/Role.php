<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'role_id',
        'role_name',
    ];

    public $timestamps = false;

    public static function getRole()
    {
        $rows = DB::select('SELECT r.* FROM roles r');
        return $rows;
    }
}
