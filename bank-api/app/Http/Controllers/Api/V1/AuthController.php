<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function login(SignInRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login($request->validated());

            return success('Login successful', $data);
        } catch (Exception $e) {
            info('Login failed!', [$e]);

            return error('Authentication failed', 401);
        }
    }

    public function register(SignUpRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->register($request->validated());

            return success('Registration successful', $data, 201);
        } catch (Exception $e) {
            info('Register failed!', [$e]);

            return error('Registration failed', 400);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout();

            return success('Logout successful');
        } catch (Exception $e) {
            info('Logout failed!', [$e]);

            return error('Logout failed', 400);
        }
    }

    public function forgetPassword(Request $request): JsonResponse
    {
        try {
            $this->authService->forgetPassword($request->validate([
                'email' => 'required|email|exists:users,email',
            ]));

            return success('Password reset link sent to your email');
        } catch (Exception $e) {
            info('Forget password failed!', [$e]);

            return error('Unable to process password reset', 400);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $this->authService->resetPassword($request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]));

            return success('Password reset successful');
        } catch (Exception $e) {
            info('Reset password failed!', [$e]);

            return error('Unable to reset password', 400);
        }
    }

    public function me(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->getCurrentUser();

            return success('User details retrieved', new UserResource($user));
        } catch (Exception $e) {
            return error('Unauthorized', 401);
        }
    }
}
