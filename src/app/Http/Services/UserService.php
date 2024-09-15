<?php

namespace App\Http\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllUsers()
    {
        return User::query()->select('id', 'name', 'email')->get();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function createUser(array $userData)
    {
        $userData['role_id'] = Role::where('name', 'user')->first()->id;
        $user = User::create($userData);

        $user['token'] = $user->createToken('Personal Access Token')->accessToken;

        return $user;
    }

    public function updateUser($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        if (!empty($data['password'])) {
            $user = Auth::user();
            $user->token()->revoke();
        }
        return $user;
    }

    public function deleteUser($id): Authenticatable
    {
        $user = Auth::user();
        $deactivateUser = DB::table('users')
            ->where('id', $id)
            ->update(['is_active' => false]);
        if ($deactivateUser) {
            $user->token()->revoke();
        }
        return $user;
    }
}
