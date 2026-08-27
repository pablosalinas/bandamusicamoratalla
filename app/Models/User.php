<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'birth_date',
        'email',
        'password',
        'role',
        'is_active',
        'leave_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function instruments()
    {
        return $this->belongsToMany(InstrumentCatalog::class, 'musician_instruments', 'user_id', 'instrument_catalog_id')->withPivot('serial_number')->withTimestamps();
    }
}
