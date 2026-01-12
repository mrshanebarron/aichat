<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Subscription</h2>

                    @if($user->isSubscribed())
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="bg-green-600 text-white text-sm font-medium px-3 py-1 rounded-full">Pro Plan</span>
                                <span class="text-green-700">Active</span>
                            </div>
                            <p class="text-gray-600 mb-4">You have unlimited access to AI chat.</p>
                            <form action="{{ route('subscription.cancel') }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel your subscription?')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    Cancel Subscription
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-8">
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <p class="text-gray-600">
                                    <span class="font-semibold text-gray-800">{{ $user->credits }}</span> free credits remaining
                                </p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Free Plan -->
                            <div class="border rounded-lg p-6">
                                <h3 class="text-lg font-semibold mb-2">Free</h3>
                                <p class="text-3xl font-bold mb-4">$0<span class="text-sm text-gray-500">/mo</span></p>
                                <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        10 free messages
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Basic AI responses
                                    </li>
                                </ul>
                                <button disabled class="w-full bg-gray-200 text-gray-500 py-2 rounded-lg cursor-not-allowed">
                                    Current Plan
                                </button>
                            </div>

                            <!-- Pro Plan -->
                            <div class="border-2 border-indigo-600 rounded-lg p-6 relative">
                                <span class="absolute -top-3 left-4 bg-indigo-600 text-white text-xs px-2 py-1 rounded">Popular</span>
                                <h3 class="text-lg font-semibold mb-2">Pro</h3>
                                <p class="text-3xl font-bold mb-4">$9.99<span class="text-sm text-gray-500">/mo</span></p>
                                <ul class="space-y-2 mb-6 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Unlimited messages
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Priority AI responses
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Multiple conversation threads
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Chat history saved forever
                                    </li>
                                </ul>
                                <form action="{{ route('subscription.checkout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                                        Subscribe Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
