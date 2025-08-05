<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function store(Request $request)
    {
        $field = $request->validate([
            'content' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        Message::create($field);

        return response()->json([
            'message' => 'Message created successfully',
            'data' => $field,
        ], 201);
    }

    public function index()
    {
        $messages = Message::orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'data' => $messages
        ], 200);
    }

    public function show($id)
    {
        $message = Message::find($id);

        return response()->json([
            'data' => $message
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $field = $request->validate([
            'content' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|integer|exists:users,id',
        ]);

        $message = Message::findOrFail($id);
        $message->update($field);

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message,
        ], 200);
    }

    public function destroy($id)
    {
        Message::destroy($id);

        return response()->json([
            'message' => 'Message deleted successfully'
        ], 200);
    }
}
