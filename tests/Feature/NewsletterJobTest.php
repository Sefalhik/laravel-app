<?php

use App\Jobs\SendNewsletterJob;
use App\Mail\NewsletterMail;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Models\User;
use App\Notifications\NewsletterSentNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

it('sends mail to every subscriber', function () {
    Mail::fake();

    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Body']);
    Subscriber::factory()->count(3)->create();
    $admin = User::factory()->create();

    (new SendNewsletterJob($newsletter->id, $admin->id))->handle();

    Mail::assertSent(NewsletterMail::class, 3);
});

it('sets sent_at after sending', function () {
    Mail::fake();
    Notification::fake();

    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Body', 'sent_at' => null]);
    $admin      = User::factory()->create();

    (new SendNewsletterJob($newsletter->id, $admin->id))->handle();

    expect($newsletter->fresh()->sent_at)->not->toBeNull();
});

it('notifies admin after sending', function () {
    Mail::fake();
    Notification::fake();

    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Body']);
    Subscriber::factory()->count(2)->create();
    $admin = User::factory()->create();

    (new SendNewsletterJob($newsletter->id, $admin->id))->handle();

    Notification::assertSentTo($admin, NewsletterSentNotification::class);
});

it('uses newsletter subject as envelope subject', function () {
    $newsletter = Newsletter::create(['subject' => 'Mon sujet', 'body' => 'Corps']);

    $envelope = (new NewsletterMail($newsletter))->envelope();

    expect($envelope)->toBeInstanceOf(Envelope::class)
        ->and($envelope->subject)->toBe('Mon sujet');
});

it('passes newsletter to the correct view', function () {
    $newsletter = Newsletter::create(['subject' => 'Sub', 'body' => 'Body']);

    $content = (new NewsletterMail($newsletter))->content();

    expect($content)->toBeInstanceOf(Content::class)
        ->and($content->view)->toBe('emails.newsletter')
        ->and($content->with['newsletter']->id)->toBe($newsletter->id);
});

it('skips sending if newsletter was already sent', function () {
    Mail::fake();

    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Body', 'sent_at' => now()]);
    Subscriber::factory()->count(3)->create();
    $admin = User::factory()->create();

    (new SendNewsletterJob($newsletter->id, $admin->id))->handle();

    Mail::assertNothingSent();
});
