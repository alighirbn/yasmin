<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;

use App\Models\Contract\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{

    public function index()
    {
        $contracts = Contract::with(['building'])->get();
        return view('map.index', compact(['contracts']));
    }

    public function due_installments()
    {
        $today = Carbon::today(); // or you can use `now()` if you want to include time

        $contracts = Contract::with(['building'])->whereHas('unpaidInstallments', function ($query) use ($today) {
            $query->where('installment_date', '<=', $today);
        })->get();

        return view('map.index', compact(['contracts']));
    }
}
