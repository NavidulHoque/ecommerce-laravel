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

    public function index(Request $request, $receiver_id)
    {
        $sender_id = $request->user->id;

        $messages = Message::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id);
        })
            ->orWhere(function ($query) use ($sender_id, $receiver_id) {
                $query->where('sender_id', $receiver_id)
                    ->where('receiver_id', $sender_id);
            })
            ->orderBy('created_at', 'desc');

        return response()->json([
            'data' => $messages,
            "message" => "Messages fetched successfully"
        ], 200);
    }

    public function update(MessageRequest $request, $id)
    {
        $field = $request->validated();
        $user = $request->user;

        $message = $this->findById(Message::class, $id);

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        else if ($field["content"] && $message->sender_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to update this message'], 403);
        }

        else if ($field["isRead"] && $message->receiver_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to update this message'], 403);
        }

        $message->update($field);

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $message = $this->findById(Message::class, $id);
        $user = $request->user;

        if (!$message) {
            return response()->json(['message' => 'Message not found'], 404);
        }

        else if ($message->sender_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to delete this message'], 403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ], 200);
    }
}
