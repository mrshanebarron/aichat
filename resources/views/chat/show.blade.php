<x-app-layout>
    <div class="flex h-[calc(100vh-64px)]">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-4">
                <form action="{{ route('chat.create') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full border border-gray-600 rounded-lg px-4 py-2 text-sm hover:bg-gray-800 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Chat
                    </button>
                </form>
            </div>

            <div class="flex-1 overflow-y-auto px-2">
                @foreach($conversations as $conv)
                    <a href="{{ route('chat.show', $conv) }}"
                       class="block px-3 py-2 rounded-lg mb-1 text-sm truncate {{ $conv->id === $conversation->id ? 'bg-gray-700' : 'hover:bg-gray-800' }}">
                        {{ $conv->title }}
                    </a>
                @endforeach
            </div>

            <div class="p-4 border-t border-gray-700">
                <div class="text-xs text-gray-400">
                    @if(auth()->user()->isSubscribed())
                        <span class="text-green-400">Pro Plan</span> - Unlimited
                    @else
                        {{ auth()->user()->credits }} credits remaining
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 flex flex-col bg-gray-50">
            <!-- Messages -->
            <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                @forelse($messages as $message)
                    <div class="flex {{ $message->role === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-2xl px-4 py-3 rounded-2xl {{ $message->role === 'user' ? 'bg-indigo-600 text-white' : 'bg-white shadow-sm border' }}">
                            <p class="whitespace-pre-wrap">{{ $message->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-lg font-medium">Start a conversation</p>
                            <p class="text-sm">Send a message to begin chatting with AI</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div class="border-t bg-white p-4">
                <form id="chat-form" class="max-w-3xl mx-auto">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text"
                               id="message-input"
                               name="message"
                               placeholder="Type your message..."
                               class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               autocomplete="off">
                        <button type="submit"
                                id="send-btn"
                                class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('chat-form');
        const input = document.getElementById('message-input');
        const messagesDiv = document.getElementById('messages');
        const sendBtn = document.getElementById('send-btn');

        function scrollToBottom() {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
        scrollToBottom();

        function addMessage(content, role) {
            const div = document.createElement('div');
            div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
            div.innerHTML = `
                <div class="max-w-2xl px-4 py-3 rounded-2xl ${role === 'user' ? 'bg-indigo-600 text-white' : 'bg-white shadow-sm border'}">
                    <p class="whitespace-pre-wrap">${content}</p>
                </div>
            `;
            messagesDiv.appendChild(div);
            scrollToBottom();
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const message = input.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, 'user');
            input.value = '';
            sendBtn.disabled = true;

            // Add loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex justify-start';
            loadingDiv.id = 'loading';
            loadingDiv.innerHTML = `
                <div class="max-w-2xl px-4 py-3 rounded-2xl bg-white shadow-sm border">
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Thinking...
                    </div>
                </div>
            `;
            messagesDiv.appendChild(loadingDiv);
            scrollToBottom();

            try {
                const response = await fetch('{{ route('chat.send', $conversation) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();

                // Remove loading
                document.getElementById('loading')?.remove();

                if (data.error) {
                    addMessage(data.error, 'assistant');
                } else {
                    addMessage(data.message, 'assistant');
                }
            } catch (error) {
                document.getElementById('loading')?.remove();
                addMessage('Something went wrong. Please try again.', 'assistant');
            }

            sendBtn.disabled = false;
            input.focus();
        });
    </script>
</x-app-layout>
