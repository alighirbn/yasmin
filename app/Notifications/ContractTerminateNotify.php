<?php

namespace App\Notifications;

use App\Models\Contract\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContractTerminateNotify extends Notification
{
    use Queueable;

    protected $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function via($notifiable)
    {
        return ['database']; // Adjust as needed (e.g., mail, sms)
    }

    public function toArray($notifiable)
    {

        return [
            'action' =>  'فسخ عقد للعقار : ' . $this->contract->building->building_number,
            'name' => $this->contract->customer ? $this->contract->customer->customer_full_name : 'اسم المشتري غير متوفر',
            'url_address' => $this->contract->url_address ?? 'رابط غير متوفر',
            'route' => 'contract.show',
        ];
    }
}
