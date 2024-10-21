<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans';

    protected $fillable = [
        'jabatan',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
