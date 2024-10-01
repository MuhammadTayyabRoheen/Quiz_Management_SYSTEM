<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Helpers\Helper;
use App\DTO\LoginDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\DTO\ResetPasswordDTO;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromRequest($request->validated());
        $result = $this->authService->login($dto);

        if (!$result) {
            return Helper::error('Unauthorized', 401);
        }

        return Helper::success($result, 'Login successful');
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            return Helper::success(null, 'Successfully logged out');
        } catch (\Exception $e) {
            return Helper::error('Failed to logout, please try again.', 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $dto = ResetPasswordDTO::fromRequest($request->validated());
        $user = User::findOrFail($dto->id);

        $success = $this->authService->resetPassword($user, $dto);

        if (!$success) {
            return Helper::error('Invalid token', 400);
        }

        return Helper::success(null, 'Password reset successfully');
    }

    public function showResetForm($id, Request $request)
    {
        $user = User::findOrFail($id);
        $token = $request->query('token');

        if ($user->reset_token !== $token) {
            return Helper::error('Invalid token', 400);
        }

        return view('auth.password-reset', ['user' => $user, 'token' => $token]);
    }
}
