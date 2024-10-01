<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    
    public function login($dto)
    {
        $credentials = [
            'email' => $dto->email,
            'password' => $dto->password,
        ];
        if (!$token = auth()->attempt($credentials)) {
            return false;
        }

        $user = auth()->user();
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');

        return [
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'id'=>$user->id
            ],
            'role' => $roles,
            'permissions' => $permissions
        ];
    }

    public function logout()
    {
        auth()->logout();
    }

    public function resetPassword($user, $dto)
    {
        // Accessing the token via the DTO object
        if ($user->reset_token !== $dto->token) {
            return false;
        }
    
        // Updating the user's password using the DTO properties
        $user->update([
            'password' => bcrypt($dto->password),
            'reset_token' => null,
        ]);
    
        return true;
    }
}
