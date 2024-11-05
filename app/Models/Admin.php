<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    protected $table = 'admins';

    public $fillable = [
        'name',
        'email',
        'role',
        'status_admin',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function booted(){
        static::creating(function ($admin) {
            $admin->password = bcrypt($admin->password);
        });
        static::updating(function ($admin) {
            if ($admin->isDirty('password')) {
                $admin->password = bcrypt($admin->password);
            }
        });
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function simpanans()
    {
        return $this->hasMany(Simpanan::class);
    }
}
