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
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    protected $service;
    protected $authService;

    public function __construct(UserService $service, AuthServiceInterface $authService)
    {
        $this->service = $service;
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     * Rules: User must provide either email or phone (not both). Password minimum length is 6.
     * Returns the created user resource on success.
     * @OA\Post(
     *   path="/auth/register",
     *   tags={"Auth"},
     *   summary="Register",
     *   description="Create a new account with either email or phone. Sending both will trigger a validation error.",
    *   @OA\RequestBody(
    *     required=true,
    *     @OA\JsonContent(
    *       required={"name","password"},
    *       @OA\Property(property="name", type="string", example="Alice", description="Full name of the user."),
    *       @OA\Property(property="email", type="string", format="email", example="alice@example.com", description="Email address (optional). Required if `phone` is not provided. Must be unique."),
    *       @OA\Property(property="phone", type="string", example="+15551234567", description="Mobile phone in international format (optional). Required if `email` is not provided. Must be unique. Validated with regex /^\\+?[1-9]\\d{7,14}$/ (E.164-like).", pattern="^\\+?[1-9]\\d{7,14}$"),
    *       @OA\Property(property="password", type="string", example="secret123", description="Account password (minimum 6 characters)."),
    *       @OA\Property(property="password_confirmation", type="string", example="secret123", description="Password confirmation (if used).")
    *     )
    *   ),
    *   @OA\Response(response=201, description="Created - returns created user resource", @OA\JsonContent(ref="#/components/schemas/User")),
    *   @OA\Response(response=422, description="Validation error - missing/invalid fields or uniqueness constraint")
    * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->service->create($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }


    /**
     * User login to obtain a JWT access token.
     * Returns 401 if credentials are invalid.
     * @OA\Post(
     *   path="/auth/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   description="Authenticate using email and password to receive a bearer token.",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", example="alice@example.com"),
     *       @OA\Property(property="password", type="string", example="secret123"),
     *     )
     *   ),
     *   @OA\Response(response=200, description="Success", @OA\JsonContent(ref="#/components/schemas/AuthResponse")),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(LoginRequest $request)
    {
        $email = $request->input('email') ?? $request->input("phone");
        $password = $request->input('password');

        $token = $this->authService->login($email, $password);
        if (! $token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return new AuthResponseResource(['access_token' => $token, 'token_type' => 'bearer']);
    }

}
