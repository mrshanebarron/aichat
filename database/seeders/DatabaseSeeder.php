<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@aichat.sbarron.com',
            'password' => Hash::make('AIChat2026!'),
            'credits' => 10,
        ]);

        // Create a sample conversation
        $conversation = Conversation::create([
            'user_id' => $user->id,
            'title' => 'Welcome to AIChat!',
            'persona' => 'assistant',
        ]);

        // Add sample messages
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => 'Hello! What can you help me with?',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => "Hi there! I'm your AI assistant. I can help you with:\n\n- Answering questions on any topic\n- Brainstorming ideas\n- Writing and editing text\n- Having friendly conversations\n- Problem solving\n\nFeel free to ask me anything!",
        ]);
    }
}
