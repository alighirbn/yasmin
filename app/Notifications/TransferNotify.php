<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
/* use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage; */
use Illuminate\Notifications\Notification;

class TransferNotify extends Notification
{
    use Queueable;

    public $request;
    /**
     * Create a new notification instance.
     */
    public function __construct($request)
    {
        $this->request = $request;
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
            'action' => ' اجراء تناقل  ',
            'name' => $this->request->oldcustomer->customer_full_name . ' الى ' . $this->request->newcustomer->customer_full_name,
            'url_address' => $this->request->url_address,
            'route' => 'transfer.show',
        ];
    }
}
