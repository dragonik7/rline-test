<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService
    ) {}

    public function login(UserLoginRequest $request)
    {
        $request->validated();

        try {
            $token = $this->userService->login($request->email, $request->password);
        } catch (BaseException $exception) {
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }

        return $this->successResponse([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    // Авторизация

    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        try {
            $token = $this->userService->register($data);
        } catch (BaseException $exception) {
            return $this->failResponse($exception->getMessage(), $exception->getCode());
        }

        return $this->successResponse([
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return $this->successResponse([], 200, "Successfully logged out");
    }

}
