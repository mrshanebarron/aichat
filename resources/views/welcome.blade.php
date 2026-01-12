<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AIChat - AI-Powered Conversations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 min-h-screen">
    <div class="relative">
        <!-- Navigation -->
        <nav class="absolute top-0 left-0 right-0 p-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <span class="text-2xl font-bold text-white">AIChat</span>
                <div class="space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('chat.index') }}" class="text-white hover:text-indigo-200 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-indigo-200 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium hover:bg-indigo-100 transition">Sign up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <div class="flex flex-col items-center justify-center min-h-screen px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Your AI Companion<br>
                <span class="text-indigo-300">Always Here to Chat</span>
            </h1>
            <p class="text-xl text-indigo-200 mb-8 max-w-2xl">
                Experience intelligent conversations powered by advanced AI. Get answers, brainstorm ideas, or just have a friendly chat.
            </p>
            <div class="flex gap-4">
                @auth
                    <a href="{{ route('chat.index') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-100 transition">
                        Start Chatting
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-100 transition">
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
                        Log In
                    </a>
                @endauth
            </div>

            <!-- Features -->
            <div class="mt-20 grid md:grid-cols-3 gap-8 max-w-4xl">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-left">
                    <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Lightning Fast</h3>
                    <p class="text-indigo-200 text-sm">Get instant responses powered by cutting-edge AI technology.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-left">
                    <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Private & Secure</h3>
                    <p class="text-indigo-200 text-sm">Your conversations are encrypted and never shared with third parties.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-left">
                    <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Multiple Chats</h3>
                    <p class="text-indigo-200 text-sm">Create unlimited conversations and keep them organized.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
