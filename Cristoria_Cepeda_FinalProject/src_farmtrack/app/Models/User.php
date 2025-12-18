<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add this relationship
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Add this method to check if user is admin
    public function isAdmin()
    {
        return $this->role && $this->role->slug === 'admin';
    }

    // Add this method to check if user is farmer
    public function isFarmer()
    {
        return $this->role && $this->role->slug === 'farmer';
    }

    // Relationship with farmer
    public function farmer()
    {
        return $this->hasOne(Farmer::class);
    }
}
