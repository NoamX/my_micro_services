<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile(): Response
    {
        return (new Response(Auth::user(), 200))
            ->header('Content-Type', 'application/json');
    }

    public function update(Request $request): Response
    {
        $user = User::find(Auth::id());

        Validator::make($request->all(), [
            'username' => [
                ['min:4', 'max:100'],
                Rule::unique('users', 'username')->ignore($user->id)
            ],
            'email' => [
                ['email', 'max:100'],
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return (new Response(['password' => ['Wrong password.']], 422))
                ->header('Content-Type', 'application/json');
        }

        $user->fill($request->except('password'));
        $user->save();

        $payload = [
            'id' => $user->id,
            'username' => $user->username,
            'iat' => Carbon::now('Europe/Paris')->timestamp,
            'exp' => Carbon::now('Europe/Paris')->addHours(12)->timestamp,
        ];

        $token = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS384');

        return (new Response(['token' => $token], 200))
            ->header('Content-Type', 'application/json');
    }

    public function delete(Request $request): Response
    {
        $user = User::find(Auth::id());

        $this->validate($request, [
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return (new Response(['password' => ['Wrong password.']], 422))
                ->header('Content-Type', 'application/json');
        }

        $user->delete();
        return (new Response('Ok', 200))
            ->header('Content-Type', 'application/json');
    }
}
