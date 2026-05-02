<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollectionStatusNotification extends Notification
{
    use Queueable;

    protected $collectionRequest;
    protected $message;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($collectionRequest, $message, $type)
    {
        $this->collectionRequest = $collectionRequest;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->collectionRequest->id,
            'message' => $this->message,
            'type' => $this->type,
            'user_name' => $this->collectionRequest->user->name ?? 'N/A',
        ];
    }
}
