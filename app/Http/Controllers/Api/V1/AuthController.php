<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetNewPassRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Mail\ConfirmationMail;
use App\Mail\RecoveryEmail;
use App\Models\Confirmation;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'message' => 'Signed up successfully, confirm your email
                        to finish the registration.',
            'user' => $user->toArray()
        ], Response::HTTP_CREATED);
    }

    public function confirm(Request $request): Response|View
    {
        $confirmation = Confirmation::where('token', $request->token)->first();
        if(!$confirmation){
            return response(['message' => 'Invalid confirmation token'],
            Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $user = User::where('email', $request->input('email'))
                ->firstOrFail();

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

    public function deleteAcc(): Response
    {
        $user = User::where('email', Auth::user()->email)->first();
        $confirmation = Confirmation::where('email', $user->email)
                        ->first();
        if($confirmation) {
            $confirmation->delete();
        }
        DB::delete('DELETE FROM "password_reset_tokens" WHERE "email"=":email"',
        ['email' => $user->email]);
        $user->tokens()->delete();
        $user->delete();
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function recover(Request $request): Response
    {
        $user = User::where('email', $request->input('email'))->first();
        if(!$user){
            return response([
                'message' => 'No account with such email!'
            ], Response::HTTP_NOT_FOUND);
        }

        $tokenExitsting = DB::select('SELECT * FROM password_reset_tokens
         WHERE email = :email', ['email' => $request->input('email')]);
        if(empty($tokenExitsting)){
            $token = str()->random(32);
            DB::insert('insert into password_reset_tokens
             (email, token, created_at) values (?, ?, ?)',
                [$request->email,
                 $token,
                 now()->toDateTimeString()]);
        } else {
            $token = $tokenExitsting[0]->token;
        }
        Mail::send(new RecoveryEmail($token, $user));

        return response([
            'message' => 'The email was sent, check your inbox!'
        ], Response::HTTP_ACCEPTED);
    }

    public function isvalidrecover(Request $request): Response
    {
        if(!$request->input('token')){
            return response([
                'message' => 'No token to check'
            ], Response::HTTP_BAD_REQUEST);
        }

        $recover = DB::select('SELECT * FROM password_reset_tokens
         WHERE token = :token', ['token' => $request->input('token')]);
        if(empty($recover)){
            return response(['message' => 'Invalid recover token.'],
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response([
            'message' => 'The token is valid!'
        ], Response::HTTP_OK);
    }

    public function setnew(SetNewPassRequest $request): Response
    {
        $data = $request->validated();
        $token = DB::select('SELECT * FROM password_reset_tokens
         WHERE token = :token', ['token' => $request->input('token')]);
        if(empty($token)){
            return response([
                'message' => 'Invalid recover token.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $token = $token[0];
        $user = User::where('email', $token->email)->first();
        DB::delete('DELETE FROM password_reset_tokens WHERE email = :email',
        ['email' => $user->email]);
        $user->password = Hash::make($data['password']);
        $user->update();
        $user->tokens()->delete();
        return response([
            'message' => 'Password was successfully updated!'
        ], Response::HTTP_ACCEPTED);
    }

    public function me(): Response
    {
        return response(Auth::user());
    }
}
