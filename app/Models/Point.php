<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'point',
        'type',
        'date',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
