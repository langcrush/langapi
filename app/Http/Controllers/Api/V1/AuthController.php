<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Mail\ConfirmationMail;
use App\Models\Confirmation;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request, UserService $service): Response
    {
        $user = $service->create($request->validated());
        $confirmation = Confirmation::create([
            'email' => $user->email,
            'token' => str()->random(32)
        ]);

        Mail::send(new ConfirmationMail($confirmation, $user));

        return response([
            'message' => 'Signed up successfully, confirm your email to finish the registration.',
            'user' => $user->toArray()
        ], Response::HTTP_CREATED);
    }

    public function confirm(Request $request): Response|View
    {
        $confirmation = Confirmation::where('token', $request->token)->first();
        if(!$confirmation){
            return response(['message' => 'Invalid confirmation token'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $confirmation->email)->first();
        $confirmation->delete();

        $user->email_verified_at = now()->toDateTimeString();
        $user->update();

        return view('confirmed');
    }

    public function login(Request $request): Response
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Incorrect email or password.',
            ], Response::HTTP_CONFLICT);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();

        if(!$user->email_verified_at) {
            return response([
                'message' => 'Email was not confirmed.'
            ], Response::HTTP_CONFLICT);
        }

        $token = $user->createToken('auth_token');

        return response([
            'message' => 'Logged in successfully.',
            'user' => $user->toArray(),
            'token' => [
                'type' => 'Bearer',
                'value' => $token->plainTextToken,
                'ttl' => config('sanctum.expiration'),
            ],
        ]);
    }

    public function logout(): Response
    {
        $token = Auth::user()->currentAccessToken();
        $token->delete();

        return response([], Response::HTTP_NO_CONTENT);
    }

    public function deleteAcc(Request $request)
    {
        $user = User::where('email', Auth::user()->email)->first();
        $confirmation = Confirmation::where('email', $user->email)->first();
        if($confirmation) {
            $confirmation->delete();
        }
        $user->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    // public function recover(Request $request)
    // {

    // }

    public function me(): Response
    {
        return response(Auth::user());
    }
}
