<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Models\User;
use App\Notifications\NewsletterSentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $newsletterId,
        public int $adminId,
    ) {}

    public function handle(): void
    {
        $newsletter = Newsletter::findOrFail($this->newsletterId);

        if ($newsletter->sent_at !== null) {
            return;
        }

        $admin = User::findOrFail($this->adminId);

        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($newsletter));
        }

        $newsletter->update(['sent_at' => now()]);

        $admin->notify(new NewsletterSentNotification($newsletter, $subscribers->count()));
    }
}
