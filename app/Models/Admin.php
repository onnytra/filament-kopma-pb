<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'admins';

    public $fillable = [
        'nama',
        'email',
        'password',
        'photo',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function activities()
    {
        return $this->hasMany(Activitie::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
