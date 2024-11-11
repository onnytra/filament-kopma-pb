<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    public $fillable = [
        'datetime',
        'status',
        'photo',
        'user_id',
        'activity_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
