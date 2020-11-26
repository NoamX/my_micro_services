<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function create(Request $request): Response
    {
        $this->validate($request, [
            'message' => 'required',
            'receiver_id' => 'required|integer'
        ]);

        $message = new Message;

        $message->fill($request->all());
        $message->user_id = Auth::id();
        $message->save();

        return (new Response(['message' => $message], 200))
            ->header('Content-Type', 'application/json');
    }

    public function read($id): Response
    {
        $message = Message::find($id);

        if (!$message) {
            return (new Response(['messsage' => ["No message with id '$id'."]], 400))
                ->header('Content-Type', 'application/json');
        }

        if (Auth::id() != $message->user_id) {
            return (new Response(['status' => 'Unauthorized.'], 401))
                ->header('Content-Type', 'application/json');
        }

        return (new Response(['message' => $message], 200))
            ->header('Content-Type', 'application/json');
    }

    public function update(Request $request, $id): Response
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $message = Message::find($id);

        if (!$message) {
            return (new Response(['messsage' => ["No message with id '$id'."]], 400))
                ->header('Content-Type', 'application/json');
        }

        if (Auth::id() != $message->user_id) {
            return (new Response(['status' => 'Unauthorized.'], 401))
                ->header('Content-Type', 'application/json');
        }

        $message->message = $request->message;
        $message->save();

        return (new Response(['message' => $message], 200))
            ->header('Content-Type', 'application/json');
    }

    public function delete($id): Response
    {
        $message = Message::find($id);

        if (!$message) {
            return (new Response(['messsage' => ["No message with id '$id'."]], 400))
                ->header('Content-Type', 'application/json');
        }

        if (Auth::id() != $message->user_id) {
            return (new Response(['status' => 'Unauthorized.'], 401))
                ->header('Content-Type', 'application/json');
        }

        $message->delete();

        return (new Response(['status' => 'Ok'], 200))
            ->header('Content-Type', 'application/json');
    }
}
