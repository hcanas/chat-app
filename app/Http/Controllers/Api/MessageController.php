<?php

namespace App\Http\Controllers\Api;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Message::with('user')
            ->latest()
            ->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = Message::create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        $message->load('user');

        NewMessageEvent::dispatch($message);

        return response()->json('', 204);
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
