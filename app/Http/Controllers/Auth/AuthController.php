<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->authService->login(
            $data['email'],
            $data['password']
        );

        if (!$user) {
            throw new \Exception('Invalid credentials', 401);
        }

        $token = $this->authService->createTokenForUser($user);

        $data = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->sendResponse(
            $data,
            'Login successful'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user) {
            $user->tokens()->delete();
        }

        return $this->sendResponse([], 'Logout successful');
    }
}
