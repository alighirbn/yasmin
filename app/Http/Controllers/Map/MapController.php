<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Models\Building\Building;
use App\Models\Contract\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MapController extends Controller
{

    public function index()
    {
        $contracts = Contract::with(['building'])->get();
        return view('map.index', compact(['contracts']));
    }

    public function building()
    {
        $buildings = Building::all();
        return view('map.building', compact('buildings'));
    }

    public function empty_building()
    {
        $buildings = Building::doesntHave('contract')->get();
        return view('map.building', compact('buildings'));
    }

    public function due_installments(Request $request)
    {
        $daysBeforeDue = $request->input('days_before_due', 0); // Default to 0 days if not provided
        $dueDate = Carbon::today()->subDays($daysBeforeDue); // Calculate the due date

        $contracts = Contract::with(['building'])->whereHas('unpaidInstallments', function ($query) use ($dueDate) {
            $query->where('installment_date', '<=', $dueDate);
        })->get();

        return view('map.index', compact(['contracts']));
    }
}
