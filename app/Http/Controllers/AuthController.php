<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResponseResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Contracts\Services\AuthServiceInterface;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $service;
    protected $authService;

    public function __construct(UserService $service, AuthServiceInterface $authService)
    {
        $this->service = $service;
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->service->create($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $token = $this->authService->login($email, $password);
        if (! $token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return new AuthResponseResource(['access_token' => $token, 'token_type' => 'bearer']);
    }
}
