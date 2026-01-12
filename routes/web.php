<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Temp route to reset demo password in PHP-FPM context
Route::get('/reset-demo', function () {
    $user = \App\Models\User::where('email', 'demo@aichat.sbarron.com')->first();
    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('AIChat2026!');
        $user->save();
        return 'Password reset for demo user';
    }
    return 'User not found';
});

Route::middleware('auth')->group(function () {
    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'create'])->name('chat.create');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/{conversation}', [ChatController::class, 'destroy'])->name('chat.destroy');

    // Subscription routes
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Stripe webhook (no auth)
Route::post('/webhook/stripe', [SubscriptionController::class, 'webhook'])->name('webhook.stripe');

require __DIR__.'/auth.php';
