<?php

namespace App\Mail;

use App\Models\Contract\Contract;
use Illuminate\Mail\Mailable;

class SendContractEmail extends Mailable
{
    public $contract;
    public $changeData;

    public function __construct(Contract $contract, array $changeData)
    {
        $this->contract = $contract;
        $this->changeData = $changeData;
    }

    public function build()
    {
        return $this->subject('اشعار بتغيير في العقود ')
            ->view('emails.contract_notification')
            ->with([
                'contract' => $this->contract,
                'changeType' => $this->changeData['type'],
                'contractData' => $this->changeData['contract_data'] ?? null,
                'oldData' => $this->changeData['old_data'] ?? null,
                'newData' => $this->changeData['new_data'] ?? null,
            ]);
    }
}
