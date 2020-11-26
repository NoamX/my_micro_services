<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    // Register
    $router->post('/register', function (Request $request): Response {
        $response = Http::post('http://localhost:8000/api/auth/register', $request->all());
        return (new Response($response->json(), $response->status()));
    });
    //Login
    $router->post('/login', function (Request $request): Response {
        $response = Http::post('http://localhost:8000/api/auth/login', $request->all());
        return (new Response($response->json(), $response->status()));
    });
    // Création d'une discussion
    $router->post('/discussion/create', function (Request $request): Response {
        $response_user = Http::withToken($request->bearerToken())
            ->get('http://localhost:8000/api/user/profile');

        if ($response_user->failed()) {
            return (new Response($response_user->json(), $response_user->status()));
        } else {
            $response_discussion = Http::withToken($request->bearerToken())
                ->post('http://localhost:9000/api/discussion', [
                    'name' => $request->name,
                    'users' => [
                        $response_user['user']['id'],
                        $request->receiver_id
                    ]
                ]);

            if ($response_discussion->failed()) {
                return (new Response($response_discussion->json(), $response_discussion->status()));
            }

            return (new Response([
                $response_user->json(), $response_discussion->json(),
            ], $response_discussion->status()));
        }
    });

    // todo : poster un message
    // todo : mettre à jour un message
    // todo : supprimer un message

    // mettre à jour une discussion
    // supprimer une discussion
});
