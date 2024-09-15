<?php

namespace App\Http\Controllers;

use App\Http\Requests\Guest\UpdateUserRequest;
use App\Http\Services\UserService;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponse;
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * User
     */
    public function getUser(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(['user' => $user], 200);
    }

    /**
     * User
     */
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->updateUser(Auth::user()->id, $request->toArray());
        return $this->successResponse($user, 'User updated');
    }

    /**
     * User
     */
    public function deactivateUser(): JsonResponse
    {
        $user = $this->userService->deleteUser(Auth::user()->id);
        return $this->successResponse($user, 'Account deactivated successfully');
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
