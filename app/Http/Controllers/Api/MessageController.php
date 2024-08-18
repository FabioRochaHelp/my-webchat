<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listMessages(User $user)
    {
        $userFrom = 1;
        $userTo = $user->id;

        $messages = Message::where(function ($query) use ($userFrom, $userTo) {
            $query->where('from', $userFrom)
                ->where('to', $userTo);
        })->orWhere(function ($query) use ($userFrom, $userTo) {
            $query->where('from', $userTo)
                ->where('to', $userFrom);
        })->orderBy('created_at', 'asc')->get();

        return response()->json(
            [
                'messages' => $messages
            ],
            Response::HTTP_OK
        );
    }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $message = new Message();
       $message->from = 1;
       $message->to = $request->to;
       $message->context = htmlspecialchars($request->context, ENT_QUOTES, 'UTF-8');
       $message->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
