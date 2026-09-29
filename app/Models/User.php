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
        'instrument_section_id',
        'is_active',
        'is_verified',
        'leave_reason',
        'address',
        'postal_code',
        'city',
        'province',
        'phone',
        'father_phone',
        'mother_phone',
        'guardian_phone',
        'joining_year',
        'privacy_accepted_at',
        'registration_ip',
        'registration_origin',
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
        'is_verified' => 'boolean',
        'iban' => 'encrypted',
        'joining_year' => 'integer',
        'privacy_accepted_at' => 'datetime',
    ];

    public function section()
    {
        return $this->belongsTo(InstrumentSection::class, 'instrument_section_id');
    }

    public function scopePendingValidation($query)
    {
        return $query->where(function($q) {
            $q->where('is_verified', false)
              ->orWhere(function($q2) {
                  $q2->where('is_active', false)
                     ->where('role', '!=', 'admin');
              });
        });
    }

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
        return $this->belongsToMany(Inventory::class, 'inventory_user', 'user_id', 'inventory_id')->withTimestamps();
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
