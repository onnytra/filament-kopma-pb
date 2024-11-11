<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kiran',
        'anonim',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
