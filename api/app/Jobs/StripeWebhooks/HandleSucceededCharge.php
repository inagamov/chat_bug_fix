<?php

namespace App\Jobs\StripeWebhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;

class HandleSucceededCharge implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        # Fetching the payload
        $charge = $this->webhookCall->payload['data']['object'];

        # Fetch user data
        $user = \App\Models\User::where('email', $charge['billing_details']['email'])->first();

        # If user is not registered as a customer yet
        if (!$user->stripe_id) {
            \App\Models\User::where('email', $charge['billing_details']['email'])->update(['stripe_id' => $charge['customer']]);
        }

        # Increase the amount of available chapters
        $chapters_access = \App\Models\ChaptersUsers::where('user_id', $user->id);
        $chapters_access->update(['chapters_available' => $chapters_access->first()['chapters_available'] + 1]);

        # Create meditations row for a new user
        if (!\App\Models\MeditationsUsers::where('user_id', $user->id)->first()) {
            \App\Models\MeditationsUsers::create([
                'user_id' => $user->id,
                'chapter_id' => 1,
                'meditation_id' => 1
            ]);
        }
    }
}