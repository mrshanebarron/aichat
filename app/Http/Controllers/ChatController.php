<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->conversations()->latest()->get();
        return view('chat.index', compact('conversations'));
    }

    public function create()
    {
        $conversation = auth()->user()->conversations()->create([
            'title' => 'New Chat',
            'persona' => 'assistant',
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $conversations = auth()->user()->conversations()->latest()->get();
        $messages = $conversation->messages()->orderBy('created_at')->get();

        return view('chat.show', compact('conversation', 'conversations', 'messages'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'message' => 'required|string|max:4000',
        ]);

        $user = auth()->user();

        if (!$user->hasCredits()) {
            return response()->json([
                'error' => 'No credits remaining. Please subscribe to continue.',
            ], 402);
        }

        // Save user message
        $conversation->messages()->create([
            'role' => 'user',
            'content' => $request->message,
        ]);

        // Update conversation title if first message
        if ($conversation->messages()->count() === 1) {
            $conversation->update([
                'title' => str()->limit($request->message, 50),
            ]);
        }

        // Build messages array for OpenAI
        $chatMessages = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'role' => $m->role,
                'content' => $m->content,
            ])
            ->toArray();

        // Add system message
        array_unshift($chatMessages, [
            'role' => 'system',
            'content' => 'You are a helpful, friendly AI assistant. Be conversational and engaging.',
        ]);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $chatMessages,
                'max_tokens' => 1000,
            ]);

            $assistantMessage = $response->choices[0]->message->content;

            // Save assistant message
            $conversation->messages()->create([
                'role' => 'assistant',
                'content' => $assistantMessage,
            ]);

            // Use credit
            $user->useCredit();

            return response()->json([
                'message' => $assistantMessage,
                'credits' => $user->fresh()->credits,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get AI response. Please try again.',
            ], 500);
        }
    }

    public function destroy(Conversation $conversation)
    {
        $this->authorize('delete', $conversation);

        $conversation->delete();

        return redirect()->route('chat.index');
    }
}
