<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    public $timestamps = false;

    public $fillable = [
    'title',
    'description',
    'datetime',
    'photo',
    'admin_id'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
