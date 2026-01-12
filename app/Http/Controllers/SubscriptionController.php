<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $user = auth()->user();
        return view('subscription.index', compact('user'));
    }

    public function checkout()
    {
        $user = auth()->user();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'customer_email' => $user->email,
            'line_items' => [[
                'price' => config('services.stripe.price_id'),
                'quantity' => 1,
            ]],
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscription.index'),
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('subscription.index');
        }

        $session = Session::retrieve($sessionId);
        $user = auth()->user();

        // Create or update subscription
        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'stripe_id' => $session->subscription,
                'stripe_status' => 'active',
                'stripe_price' => config('services.stripe.price_id'),
            ]
        );

        return redirect()->route('chat.index')->with('success', 'Subscription activated! Enjoy unlimited chats.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response('Webhook Error', 400);
        }

        switch ($event->type) {
            case 'customer.subscription.updated':
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                Subscription::where('stripe_id', $subscription->id)->update([
                    'stripe_status' => $subscription->status,
                    'ends_at' => $subscription->cancel_at ? now()->timestamp($subscription->cancel_at) : null,
                ]);
                break;
        }

        return response('OK', 200);
    }

    public function cancel()
    {
        $user = auth()->user();

        if ($user->subscription) {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $stripe->subscriptions->cancel($user->subscription->stripe_id);

            $user->subscription->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);
        }

        return redirect()->route('subscription.index')->with('success', 'Subscription canceled.');
    }
}
