<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

enum UserRole: string
{
    case Staff = 'staff';
    case Manager = 'manager';
    case Admin = 'admin';
}

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }

    public function isStaff(): bool
    {
        return $this->role === UserRole::Staff;
    }

    public function canApprove(): bool
    {
        return in_array($this->role, [UserRole::Manager, UserRole::Admin]);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function approvalLogs()
    {
        return $this->hasMany(ApprovalLog::class);
    }
}
