<?php

namespace App\Modules\Batching\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BatchOrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) { }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Batch Order')
            ->greeting("Dear {$notifiable->name},")
            ->line('You have a new order from a provider on Curacel.')
            ->line("**Provider name**: {$this->order->provider_name}")
            ->line("**Batch**: {$this->order->batch->name}")
            ->line('If you do not recognize this order, please reach out to our support to fix it.')
            ->line('Thank you for using Curacel!')
            ->salutation('Regards from the team at Curacel');
    }
}
