<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'account_number',
        'balance',
        'status',
    ];

    public $timestamps = false;
}
