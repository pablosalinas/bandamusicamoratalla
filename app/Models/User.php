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
        'nif',
        'birth_date',
        'email',
        'password',
        'role',
        'is_active',
        'leave_reason',
        'address',
        'postal_code',
        'city',
        'province',
        'phone',
        'iban',
        'photo_path',
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
        'iban' => 'encrypted',
    ];

    public function isSuperAdmin(): bool
    {
        return str_contains($this->email, 'pabloeltortas');
    }

    public function canViewIban(): bool
    {
        return $this->role === 'treasurer' || $this->isSuperAdmin();
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'user_id');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        
        // Default avatar based on name
        $name = urlencode($this->name . ' ' . $this->last_name);
        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }

    public function isCurrentBoardMember(): bool
    {
        return \App\Models\BoardMember::where('user_id', $this->id)
            ->whereHas('board', function ($q) {
                $q->where('is_active', true);
            })->exists();
    }
}
