<?php

namespace App\Http\Controllers;

use Illuminate\Console\Application as ApplicationAlias;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\ResponseFactory;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    const CODE_SUCCESS_200 = 200;
    const CODE_SUCCESS_CREATE_201 = 201;
    const CODE_SUCCESS_ACCEPTED_202 = 202;
    const NO_CONTENT_204 = 204;
    const CODE_VALIDATION_ERROR_400 = 400;
    const CODE_GRANT_ACCESS_ERROR_401 = 401;
    const CODE_NOT_FOUND_404 = 404;
    const CODE_ERROR_409 = 409;
    const CODE_METHOD_FAILURE_420 = 420;
    const CODE_BAD_REQUEST_422 = 422;
    const CODE_BACKEND_ERROR_500 = 500;

    /**
     * @param  array|JsonResource  $data
     * @param  int  $code
     * @param  string  $message
     * @return ApplicationAlias|ResponseFactory|Application|Response
     */
    public function successResponse(array|JsonResource $data = [], int $code = self::CODE_SUCCESS_200, string $message = 'Success'): ApplicationAlias|ResponseFactory|Application|Response
    {
        return response(['message' => $message, 'data' => $data], $code);
    }

    /**
     * @param  array|JsonResource  $data
     * @param  int  $code
     * @param  string  $message
     * @param  array  $errors
     * @return Application|ApplicationAlias|Response|ResponseFactory
     */
    public function failResponse(string $message = 'Error', int $code = self::CODE_BACKEND_ERROR_500, array $errors = [], array|JsonResource $data = []): ApplicationAlias|ResponseFactory|Application|Response
    {
        return response(['message' => $message, 'data' => $data, 'errors' => $errors], $code, $data);
    }
}