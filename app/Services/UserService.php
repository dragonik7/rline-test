<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function register(array $userData): string
    {
        $user = User::create([
            'name'     => $userData['name'],
            'email'    => $userData['email'],
            'password' => $userData['password'],
        ]);

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function login(string $email, string $password): string
    {
        $user = User::where('email', $email)->firstOrFail();

        if (!Hash::check($password, $user->password)) {
            throw new BaseException("The provided credentials are incorrect.", 422);

        }

        return $user->createToken('auth_token')->plainTextToken;
    }
}