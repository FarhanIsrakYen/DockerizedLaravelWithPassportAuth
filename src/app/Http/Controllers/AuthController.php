<?php

namespace App\Http\Controllers;

use App\Http\Requests\Guest\LoginRequest;
use App\Http\Requests\Guest\RegisterRequest;
use App\Http\Services\UserService;
use App\Http\Traits\ApiResponse;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    use ApiResponse;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * User registration
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $user = $this->userService->createUser($userData);

        return $this->successResponse($user);
    }

    /**
     * User login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account was deactivated'
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
        $user['token'] = $user->createToken('Personal Access Token')->accessToken;

        return response()->json(['user' => $user], 200);
    }
}
