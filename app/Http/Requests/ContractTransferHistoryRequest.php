<?php

namespace App\Http\Requests;

use App\Models\Contract\Contract;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ContractTransferHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id',
            'url_address' => ['required'],
            'user_id_create' => ['Numeric'],
            'user_id_update' => ['Numeric'],


            //foreign id and reference
            'old_customer_id' => ['required'],
            'new_customer_id' => ['required'],
            'contract_id' => ['required'],
            'old_customer_picture' => 'nullable|string',
            'new_customer_picture' => 'nullable|string',

            //normal fields
            'transfer_date' => ['required', 'date_format:Y-m-d'],
            'transfer_amount' => ['required'],
            'transfer_note' => ['max:200'],
        ];
    }

    protected function prepareForValidation()
    {
        //add url address
        $this->mergeIfMissing(['url_address' => $this->get_random_string(60)]);

        $this->mergeIfMissing(['old_customer_id' => $this->get_old_customer_id()]);

        //add user_id base on route
        if (request()->routeIs('transfer.store')) {
            $this->mergeIfMissing(['user_id_create' => auth()->user()->id]);
        } elseif (request()->routeIs('transfer.update')) {
            $this->mergeIfMissing(['user_id_update' =>  auth()->user()->id]);
        }
    }


    function get_old_customer_id()
    {
        $contract = Contract::where('id', $this->contract_id)->first();

        return $contract ? $contract->customer->id : null;
    }

    function get_random_string($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }
}
