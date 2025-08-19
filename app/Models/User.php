<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
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

    public static function make(string $email, string $password): static
    {
        return new static([
            'uuid' => Str::uuid()->toString(),
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (self $user) {
            if (empty($user->getKey())) {
                $user->setAttribute($user->getKeyName(), Str::uuid()->toString());
            }
        });
    }
}
