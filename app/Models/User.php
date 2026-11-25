<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir' || $this->role === 'admin';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'kasir', 'admin' => 'Kasir / Front Office',
            'teknisi' => 'Teknisi Laptop',
            'owner' => 'Owner / Pimpinan',
            default => ucfirst($this->role),
        };
    }

    public function getRoleBadge(): string
    {
        return match ($this->role) {
            'kasir', 'admin' => 'bg-sky-100 text-sky-800 border-sky-300',
            'teknisi' => 'bg-amber-100 text-amber-800 border-amber-300',
            'owner' => 'bg-purple-100 text-purple-800 border-purple-300',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }
}
