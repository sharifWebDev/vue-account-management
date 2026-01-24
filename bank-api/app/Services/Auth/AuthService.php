<?php

namespace App\Services\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function __construct(protected User $model) {}

    public function login(array $data): array
    {
        $user = $this->model->where('email', $data['email'])->first();

        if (! $user) {
            throw new HttpException(404, 'User not found');
        }

        if (! Hash::check($data['password'], $user->password)) {
            throw new HttpException(401, 'Invalid credentials'.$user->password);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function register(array $request): array
    {
        $request['password'] = Hash::make($request['password']);

        $user = User::create($request);

        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function logout(): void
    {
        Auth::user()->currentAccessToken()->delete();
    }

    public function forgetPassword(array $request): void
    {
        $status = Password::sendResetLink(['email' => $request['email']]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new Exception(__($status));
        }
    }

    public function resetPassword(array $request): void
    {
        $status = Password::reset(
            [
                'email' => $request['email'],
                'password' => $request['password'],
                'password_confirmation' => $request['password_confirmation'],
                'token' => $request['token'],
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new Exception(__($status));
        }
    }

    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }
}
