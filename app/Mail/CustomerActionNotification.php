<?php

namespace App\Mail;

use App\Models\Customer\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerActionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $action;
    public $note;

    public function __construct(Customer $customer, $action, $note = null)
    {
        $this->customer = $customer;
        $this->action = $action;
        $this->note = $note;
    }

    public function build()
    {
        return $this->subject('اشعار بتغيير في الزبائن')
            ->view('emails.customer_action')
            ->with([
                'customer' => $this->customer,
                'action' => $this->action,
                'note' => $this->note,
            ]);
    }
}
