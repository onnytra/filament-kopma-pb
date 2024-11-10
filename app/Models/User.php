<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'photo',
        'status_user',
        'jabatan_id',
        'nia',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function booted(){
        static::creating(function ($user) {
            $user->password = bcrypt($user->password);
        });
        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = bcrypt($user->password);
            }
        });
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    public function kirans()
    {
        return $this->hasMany(Kiran::class);
    }
    
    public function points()
    {
        return $this->hasMany(Point::class);
    }
}
