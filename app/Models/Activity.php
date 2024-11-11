<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    public $fillable = [
        'activity',
        'description',
        'datetime',
        'place',
        'status',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
