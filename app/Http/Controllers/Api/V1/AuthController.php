<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Incorrect email or password.',
            ], Response::HTTP_CONFLICT);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();

        $token = $user->createToken('auth_token');

        return response([
            'message' => 'Logged in successfully.',
            'user' => $user->toArray(),
            'token' => [
                'type' => 'Bearer',
                'value' => $token->plainTextToken,
                // 'ttl' => config('sanctum.expiration'),
            ],
        ]);
    }
}
