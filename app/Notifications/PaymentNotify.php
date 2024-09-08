<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
/* use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage; */
use Illuminate\Notifications\Notification;

class PaymentNotify extends Notification
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
        // Check if $this->request and its nested properties are not null
        $contractInstallment = $this->request->contract_installment ?? null;
        $installment = $contractInstallment->installment ?? null;
        $customer = $this->request->contract->customer ?? null;

        return [
            'action' => $installment ? 'استلام الدفعة ' . $installment->installment_name : 'استلام دفعة ',
            'name' => $customer ? $customer->customer_full_name : 'اسم العميل غير متوفر',
            'url_address' => $this->request->url_address ?? 'رابط غير متوفر',
            'route' => 'payment.show',
        ];
    }
}
