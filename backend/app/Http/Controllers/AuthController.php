<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register new user in application.
     *
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create(array_merge($request->validated(), ['password' => bcrypt($request->password)]));

        return response()->json(UserResource::make($user), Response::HTTP_CREATED);
    }

    /**
     * Get new token for user via given credentials.
     *
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = JWTAuth::attempt($request->validated())) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => UserResource::make(auth()->user())
        ]);
    }
}
