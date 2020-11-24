<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request): Response
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username|min:4|max:100',
            'email' => 'required|unique:users,email|email|max:100',
            'password' => 'required|min:8|max:100',
        ]);

        $user = new User;

        $user->fill($request->except('password'));
        $user->password = Hash::make($request->password);

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

    public function login(Request $request): Response
    {
        $this->validate($request, [
            'username' => 'required|min:4|max:100',
            'password' => 'required|max:100',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return (new Response(['error' => "No account associated with '$request->username'."], 422))
                ->header('Content-Type', 'application/json');
        } else {
            if (!Hash::check($request->password, $user->password)) {
                return (new Response(['error' => "Wrong password or username."], 422))
                    ->header('Content-Type', 'application/json');
            } else {
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
        }
    }
}
