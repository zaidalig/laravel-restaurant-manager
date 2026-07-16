<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function canManageUsers(): bool
    {
        return in_array($this->role, ['owner', 'manager'], true);
    }

    public function canManageRestaurant(): bool
    {
        return in_array($this->role, ['owner', 'manager'], true);
    }

    public function canManageOrders(): bool
    {
        return in_array($this->role, ['owner', 'manager', 'waiter'], true);
    }

    public function canManageReservations(): bool
    {
        return in_array($this->role, ['owner', 'manager', 'host'], true);
    }
}
