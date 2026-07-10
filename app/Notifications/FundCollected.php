<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class FundCollected extends Notification
{
    /**
     * Scalar payload (not the Eloquent model) so the notification is safe to
     * serialize/queue and never triggers a lazy DB load at send time.
     */
    public function __construct(
        public string $typeLabel,     // খেদমত / কল্যাণ / ভাড়া
        public string $memberName,
        public string $collectorName,
        public float $amount,
        public int $recordId
    ) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    protected function title(): string
    {
        return 'নতুন ' . $this->typeLabel . ' সংগ্রহ';
    }

    protected function body(): string
    {
        return $this->collectorName . ' — ' . $this->memberName . ' এর ' . $this->typeLabel
            . ' ' . number_format($this->amount) . ' টাকা সংগ্রহ করেছেন।';
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title(),
            'message' => $this->body(),
            'type' => $this->typeLabel,
            'amount' => $this->amount,
            'member' => $this->memberName,
            'collector' => $this->collectorName,
            'record_id' => $this->recordId,
            'url' => route('dashboard'),
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($this->title())
            ->icon('/assets/images/logo-2.png')
            ->badge('/assets/images/logo-2.png')
            ->body($this->collectorName . ' — ' . $this->memberName . ', ' . number_format($this->amount) . ' টাকা')
            ->data(['url' => route('dashboard')])
            ->options(['TTL' => 3600]);
    }
}
