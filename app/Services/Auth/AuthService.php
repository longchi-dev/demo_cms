<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected AuthRepository $authRepository,
    ) {}

    public function login(string $email, string $password): ?User
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function createTokenForUser(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
