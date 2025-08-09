<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function store(MessageRequest $request)
    {
        $field = $request->validated();
        $field['sender_id'] = $request->user->id;

        Message::create($field);

        return response()->json([
            'message' => 'Message created successfully',
            'data' => $field,
        ], 201);
    }

    public function index(Request $request)
    {
        $sender_id = $request->user->id;

        $messages = Message::where(function ($query) use ($sender_id) {
            $query->where('sender_id', $sender_id)
                ->orWhere('receiver_id', $sender_id);
        })
            ->orderBy('created_at', 'desc')
            ->paginate($request->limit);

        return response()->json([
            'data' => $messages,
            "message" => "Messages fetched successfully"
        ], 200);
    }

    public function update(MessageRequest $request, $id)
    {
        $field = $request->validated();

        $message = $this->findById(Message::class, $id);

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $message->update($field);

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message,
        ], 200);
    }

    public function destroy($id)
    {
        $message = $this->findById(Message::class, $id);

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ], 200);
    }
}
