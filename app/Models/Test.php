<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'method',
        'function',
        'variable',
        'value',
        'expected_validity',
        'actual_validity',
        'status',
        'reason',
    ];
}
