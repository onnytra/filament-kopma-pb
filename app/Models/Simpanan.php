<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'amount',
        'date',
        'proof',
        'status',
        'description',
        'point',
        'voluntary_amount',
        'admin_id',
        'user_id',
    ];
}
